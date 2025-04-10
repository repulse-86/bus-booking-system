<script setup>
import axios from 'axios';
import { ref, computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Label from '@/Components/Label.vue';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import { showConfirmation, showAlert } from '@/helpers';

const { auth } = usePage().props;

const props = defineProps({
    bus: {
        type: Object,
        required: true,
    },
});

const takenSeats = ref([]);

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
        left: [i, i + 1].filter(seat => seat <= props.bus.available_seats),
        right: [i + 2, i + 3].filter(seat => seat <= props.bus.available_seats),
    });
}

const toggleSeatModal = () => {
    isOpenSeatModal.value = !isOpenSeatModal.value;
};

const selectSeat = (seat) => {
    if (form.seats.includes(seat)) {
        form.seats = form.seats.filter(s => s !== seat);
    } else {
        form.seats.push(seat);
    }
};

watch(() => form.travel_date, async (newDate) => {
    if (!newDate) return;

    form.reset("seats");

    try {
        const response = await axios.get(route('customer.taken-seats'), {
            params: {
                travel_date: newDate,
                bus_id: props.bus.id,
            }
        });

        takenSeats.value = response.data.taken_seats;
        console.log(takenSeats.value);
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
    /*const now = new Date();
    const [startHour, startMinute] = props.bus.time_available_start.split(':').map(Number);
    const [endHour, endMinute] = props.bus.time_available_end.split(':').map(Number);

    const start = new Date();
    start.setHours(startHour, startMinute, 0, 0);

    const end = new Date();
    end.setHours(endHour, endMinute, 0, 0);

    const withinTimeRange = now >= start && now <= end;*/
    const allSeatsTaken = takenSeats.value.length >= props.bus.available_seats;

    return !allSeatsTaken;
});

</script>

<template>
    <AppLayout :title="bus.bus_type + ' Booking Form'">
        <template #header>
            <div class="flex gap-2">
                <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                    Booking Form for {{ bus.bus_type }}
                </h2>
                <span
                    v-if="!isBusAvailableNow"
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100"
                >
                    Unavailable
                </span>
                <span
                    v-else
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100"
                >
                    Available
                </span>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-3 sm:grid-cols-1 gap-4">
                <form @submit.prevent="submitForm" class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                        <div class="space-y-2">
                            <h1 class="text-4xl dark:text-gray-300 font-semibold">Booking Details</h1>
                            <div class="space-y-4">
                                <p class="text-lg text-gray-600 dark:text-gray-400">Choose your seat and select your preferred travel date to complete the booking.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
                            <div class="space-y-6 flex flex-col justify-center">
                                <div>
                                    <Label>Departure Location</Label>
                                    <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.departure_location }}</p>
                                </div>
                                <div>
                                    <Label>Destination Location</Label>
                                    <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.destination_location }}</p>
                                </div>
                            </div>
                            <div class="space-y-6 flex flex-col justify-center">
                                <div>
                                    <Label>Number of Seats</Label>
                                    <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.available_seats }}</p>
                                </div>
                                <div>
                                    <Label>Price Per Ticket</Label>
                                    <p class="text-lg text-gray-800 dark:text-gray-300">P {{ bus.price_per_ticket }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto grid lg:grid-cols-2 gap-6">
                            <div>
                                <InputLabel value="Travel Date" class="text-lg font-medium mb-2" />
                                <TextInput
                                    v-model="form.travel_date"
                                    type="date"
                                    class="w-full text-dark dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-500 appearance-none"
                                 />
                                <InputError class="mt-2 text-red-500" :message="form.errors.travel_date" />
                            </div>
                            <div cl>
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

                        <PrimaryButton class="py-3 flex justify-center items-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</PrimaryButton>
                    </div>
                </form>
                <div class="">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                        <div class="space-y-1">
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                                Important Notice
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                You must pay the terminal staff before the bus departs on your scheduled travel date.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isOpenSeatModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 h-4/5 overflow-hidden flex flex-col space-y-8">
                <h3 class="text-xl dark:text-gray-300 font-bold mb-2">Bus Seat Layout</h3>
                <div class="flex-1 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-12">
                        <div class="space-y-4">
                            <div
                                v-for="(row, index) in seatRows"
                                :key="'left-row-' + index"
                                class="grid grid-cols-2 gap-2"
                            >
                                <div v-for="seat in row.left" :key="'seat-' + seat" class="text-center">
                                    <button
                                        type="button"
                                        @click="selectSeat(seat)"
                                        :disabled="takenSeats.includes(seat)"
                                        :class="{
                                            'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800': form.seats.includes(seat),
                                            'bg-gray-200 dark:bg-gray-700 text-black dark:text-white': !form.seats.includes(seat),
                                            'bg-red-300 dark:bg-red-700 text-white opacity-75 cursor-not-allowed': takenSeats.includes(seat)
                                        }"
                                        class="w-full py-2 px-4 rounded-lg font-medium"
                                    >
                                        {{ seat }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="(row, index) in seatRows"
                                :key="'right-row-' + index"
                                class="grid grid-cols-2 gap-2"
                            >
                                <div v-for="seat in row.right" :key="'seat-' + seat" class="text-center">
                                    <button
                                        type="button"
                                        @click="selectSeat(seat)"
                                        :disabled="takenSeats.includes(seat)"
                                        :class="{
                                            'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800': form.seats.includes(seat),
                                            'bg-gray-200 dark:bg-gray-700 text-black dark:text-white': !form.seats.includes(seat),
                                            'bg-red-500 dark:bg-red-700 text-white opacity-75 cursor-not-allowed': takenSeats.includes(seat)
                                        }"
                                        class="w-full py-2 px-4 rounded-lg font-medium"
                                    >
                                        {{ seat }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <SecondaryButton
                    type="button"
                    class="p-4 w-full justify-center"
                    @click="toggleSeatModal"
                >
                    Close
                </SecondaryButton>
            </div>
        </div>

    </AppLayout>
</template>
