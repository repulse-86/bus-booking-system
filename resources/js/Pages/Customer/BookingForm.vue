<script setup>
import axios from 'axios';
import { ref, computed, watch, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import BookingFormHeader from '@/Components/BookingFormHeader.vue';
import BusDetailsStatic from '@/Components/BusDetailsStatic.vue';
import ImportantNotice from '@/Components/ImportantNotice.vue';
import SeatSelectionModal from '@/Components/SeatSelectionModal.vue';
import { showConfirmation, showAlert } from '@/helpers';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css'

const { auth } = usePage().props;

const props = defineProps({
    bus: {
        type: Object,
        required: true,
    },
});

const isDateDisabled = (date, busId) => {
    const startOfYear = new Date(date.getFullYear(), 0, 1);
    const day = new Date(startOfYear);
    while (day.getDay() !== 0) day.setDate(day.getDate() - 1);

    const diffInDays = Math.floor((date - day) / (1000 * 60 * 60 * 24));
    const weekIndex = Math.floor(diffInDays / 7);
    const isEvenWeek = weekIndex % 2 === 0;

    if (busId >= 1 && busId <= 10) return !isEvenWeek;
    if (busId >= 11 && busId <= 15) return isEvenWeek;
    return false;
};

const takenSeats = ref([]);
onMounted(() => {
    flatpickr('#calendar', {
        disable: [
            function(date) {
                return isDateDisabled(date, props.bus.id);
            }
        ],
        dateFormat: 'Y-m-d',
        onDayCreate: (dObj, dStr, fp, dayElem) => {
            const date = dayElem.dateObj;
            if (isDateDisabled(date, props.bus.id)) {
                dayElem.innerHTML = `<span style="text-decoration: line-through; color: #999;">${dayElem.innerText}</span>`;
            }
        }
    });
});

const form = useForm({
    bus_id: props.bus.id,
    customer_id: auth.user.id,
    seats: [],
    travel_date: null,
});

const isOpenSeatModal = ref(false);
const selectedSeatLabel = computed(() => {
    return form.seats.length ? `Seats: ${form.seats.join(', ')}` : 'Select Seat';
});

const seatRows = ref([]);
for (let i = 1; i <= props.bus.available_seats; i += 4) {
    seatRows.value.push({
        left: [i, i + 1].filter(seat => seat <= props.bus.available_seats).map(String),
        right: [i + 2, i + 3].filter(seat => seat <= props.bus.available_seats).map(String),
    });
}

const toggleSeatModal = () => {
    isOpenSeatModal.value = !isOpenSeatModal.value;
};

const selectSeat = (seat) => {
    const seatStr = String(seat);
    if (form.seats.includes(seatStr)) {
        form.seats = form.seats.filter(s => s !== seatStr);
    } else {
        form.seats.push(seatStr);
    }
};

watch(() => form.travel_date, async (newDate) => {
    if (!newDate) return;
    form.reset("seats");
    try {
        const response = await axios.get(route('customer.taken-seats'), {
            params: { travel_date: newDate, bus_id: props.bus.id }
        });
        takenSeats.value = response.data.taken_seats.map(String);
    } catch (error) {
        console.error("Error fetching taken seats:", error);
    }
});

const submitForm = () => {
     showConfirmation({
        title: 'Are you sure you want to book this ticket?',
        text: 'Please review your details before proceeding. This action cannot be undone.',
        confirmButtonText: 'Yes, book it!',
        cancelButtonText: 'No, cancel'
     }).then((result) => {
        if (result.isConfirmed) {
            form.post(route('customer.bookings.store'), {
                onSuccess: () => {
                    showAlert({
                        icon: 'success',
                        title: 'Ticket Booked Successfully!',
                        text: 'Your ticket has been booked and is awaiting approval. You will receive a confirmation email shortly.',
                    });
                    window.location.href = route('customer.bookings.index');
                },
                onError: (error) => {
                    showAlert({
                        icon: 'error',
                        title: 'Ticket Booking Failed!',
                        text: 'There was an issue with your booking. Please try again.',
                    });
                    console.error(error);
                },
            });
        }
    })
};

const isBusAvailableNow = computed(() => {
    if (!form.travel_date) return true;
    return takenSeats.value.length < props.bus.available_seats;
});
</script>

<template>
    <AppLayout :title="bus.bus_type + ' Booking Form'">
        <template #header>
            <BookingFormHeader :busType="bus.bus_type" :isBusAvailableNow="isBusAvailableNow" />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-3 sm:grid-cols-1 gap-4">
                <form @submit.prevent="submitForm" class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                        <div class="space-y-2">
                            <h1 class="text-4xl dark:text-gray-300 font-semibold">Booking Details</h1>
                            <p class="text-lg text-gray-600 dark:text-gray-400">Choose your seat and select your preferred travel date to complete the booking.</p>
                        </div>

                        <BusDetailsStatic :bus="bus" />

                        <div class="mt-auto grid lg:grid-cols-2 gap-6">
                            <div>
                                <InputLabel value="Travel Date" class="text-lg font-medium mb-2" />
                                <TextInput
                                    id="calendar"
                                    v-model="form.travel_date"
                                    class="w-full"
                                    placeholder="Select a date"
                                 />
                                <InputError class="mt-2 text-red-500" :message="form.errors.travel_date" />
                            </div>
                            <div>
                                <InputLabel v-if="form.travel_date" value="Select Seat" class="text-lg font-medium mb-2" />
                                <InputLabel v-else value="To select seats, set your travel date first" class="text-lg font-medium mb-2" />
                                <SecondaryButton
                                    type="button"
                                    @click="toggleSeatModal"
                                    class="w-full py-3"
                                    :disabled="!form.travel_date"
                                >
                                    {{ selectedSeatLabel }}
                                </SecondaryButton>
                                <InputError class="mt-2 text-red-500" :message="form.errors.seats" />
                            </div>
                        </div>

                        <PrimaryButton class="py-3 flex justify-center items-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !isBusAvailableNow">Submit</PrimaryButton>
                    </div>
                </form>
                <div class="lg:col-span-1">
                    <ImportantNotice />
                </div>
            </div>
        </div>

        <SeatSelectionModal
            :isOpenSeatModal="isOpenSeatModal"
            :seatRows="seatRows"
            :takenSeats="takenSeats"
            :selectedSeats="form.seats"
            @toggle-modal="toggleSeatModal"
            @select-seat="selectSeat"
        />
    </AppLayout>
</template>
