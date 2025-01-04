<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Label from '@/Components/Label.vue';

const { auth } = usePage().props;

const props = defineProps({
    bus: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    bus_id: props.bus.id,
    customer_id: auth.user.id,
    seat: null,
    travel_date: null,
    payment_image: null,
});

const modalOpen = ref(false);
const selectedSeatLabel = ref('Select Seat');

const seatRows = ref([]);
for (let i = 1; i <= props.bus.available_seats; i += 4) {
    seatRows.value.push({
        left: [i, i + 1].filter(seat => seat <= props.bus.available_seats),
        right: [i + 2, i + 3].filter(seat => seat <= props.bus.available_seats),
    });
}   

const openModal = () => {
    modalOpen.value = true;
};

const closeModal = () => {
    modalOpen.value = false;
};

const selectSeat = (seat) => {
    form.seat = seat;
    selectedSeatLabel.value = `Seat ${seat}`;
};

const submitForm = () => {
    form.post(route('customer.booked-tickets.store'), {
        onSuccess: () => {
            alert('Success');
            resetForm();
        },
        onError: (error) => {
            alert('Error');
            console.error(error);
        },
    });
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    form.payment_image = file;
};

const resetFileInput = () => {
    document.getElementById('file').value = '';
};

const resetForm = () => {
    form.reset();
    selectedSeatLabel.value = 'Select seat';
    resetFileInput();
};
</script>

<template>
    <AppLayout :title="bus.bus_type + ' Booking Form'">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                Booking Form for {{ bus.bus_type }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submitForm" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                    <!-- Left Column -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                        <h1 class="text-4xl font-semibold">Booking Details</h1>
                        <div class="space-y-4">
                            <p class="text-lg text-gray-600">Choose your seat and select your preferred travel date to complete the booking.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
                            <div class="space-y-6 flex flex-col justify-center">
                                <div>
                                    <Label>Departure Location</Label>
                                    <p class="text-lg text-gray-800">{{ bus.departure_location }}</p>
                                </div>
                                <div>
                                    <Label>Destination Location</Label>
                                    <p class="text-lg text-gray-800">{{ bus.destination_location }}</p>
                                </div>
                            </div>
                            <div class="space-y-6 flex flex-col justify-center">
                                <div>
                                    <Label>Number of Seats</Label>
                                    <p class="text-lg text-gray-800">{{ bus.available_seats }}</p>
                                </div>
                                <div>
                                    <Label>Price Per Ticket</Label>
                                    <p class="text-lg text-gray-800">P {{ bus.price_per_ticket }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto grid lg:grid-cols-2 gap-6">
                            <div>
                                <InputLabel value="Select Seat" class="text-lg font-medium mb-2" />
                                <SecondaryButton 
                                    type="button" 
                                    @click="openModal"
                                    class="w-full py-3"
                                >
                                    {{ selectedSeatLabel }}
                                </SecondaryButton>
                                <InputError class="mt-2 text-red-500" :message="form.errors.seat" />
                            </div>

                            <div>
                                <InputLabel value="Travel Date" class="text-lg font-medium mb-2" />
                                <TextInput v-model="form.travel_date" type="date" class="w-full" />
                                <InputError class="mt-2 text-red-500" :message="form.errors.travel_date" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                            <h1 class="text-4xl font-semibold">Payment Information</h1>
                            <div class="space-y-4">
                                <p class="text-lg text-red-600">Choose your seat and select your preferred travel date to complete the booking.</p>
                            </div>
                            <div class="space-y-6">
                                <p class="text-xl font-medium text-gray-900">
                                    <strong>GCash Number:</strong> <span class="font-bold text-blue-600">0917-123-4567</span>
                                </p>

                                <div class="">
                                    <InputLabel value="Upload Payment Image" class="text-lg font-medium mb-2" />
                                    <TextInput 
                                        type="file"
                                        class="mt-1 w-full py-2 px-4 border rounded-lg"
                                        @change="handleFileChange"
                                        id="file" 
                                    />
                                    <InputError class="mt-2 text-red-500" :message="form.errors.payment_image" />
                                </div>
                            </div>
                            
                        <PrimaryButton class="py-3 flex justify-center items-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</PrimaryButton>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- Seat Selection Modal -->
        <div v-if="modalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 h-4/5 overflow-hidden flex flex-col space-y-8">
                <h3 class="text-xl font-bold mb-2">Bus Seat Layout</h3>
                <div class="flex-1 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-12">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div 
                                v-for="row in seatRows" 
                                :key="'left-' + row.left" 
                                class="grid grid-cols-2 gap-2"
                            >
                                <div 
                                    v-for="seat in row.left" 
                                    :key="'seat-' + seat" 
                                    class="text-center"
                                >
                                    <button 
                                        type="button" 
                                        @click="selectSeat(seat)"
                                        :class="{
                                            'bg-blue-600 text-white': form.seat === seat,
                                            'bg-gray-200 dark:bg-gray-700 text-black dark:text-white': form.seat !== seat
                                        }"
                                        class="w-full py-2 px-4 rounded-lg font-medium"
                                    >
                                        {{ seat }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div 
                                v-for="row in seatRows" 
                                :key="'right-' + row.right" 
                                class="grid grid-cols-2 gap-2"
                            >
                                <div 
                                    v-for="seat in row.right" 
                                    :key="'seat-' + seat" 
                                    class="text-center"
                                >
                                    <button 
                                        type="button" 
                                        @click="selectSeat(seat)"
                                        :class="{
                                            'bg-blue-600 text-white': form.seat === seat,
                                            'bg-gray-200 dark:bg-gray-700 text-black dark:text-white': form.seat !== seat
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
                <button 
                    type="button" 
                    class="w-full py-2 px-4 bg-red-600 text-white font-medium rounded-lg"
                    @click="closeModal"
                >
                    Close
                </button>
            </div>
        </div>

    </AppLayout>
</template>
