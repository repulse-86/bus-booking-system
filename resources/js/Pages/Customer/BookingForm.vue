<script setup>
import { ref } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Label from '@/Components/Label.vue';
import VueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';
import { showConfirmation, showAlert } from '@/helpers'; 

const FilePond = VueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

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

const handleFilePondLoad = (response) => {
    form.payment_image = response;
}

const handleFilePondRevert = () => {
    router.delete(route('customer.payment-receipt-revert', form.payment_image));
    form.payment_image = null;
}
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
                    <div class="">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                            <h1 class="text-4xl dark:text-gray-300 font-semibold">Booking Details</h1>
                            <div class="space-y-4">
                                <p class="text-lg text-gray-600 dark:text-gray-400">Choose your seat and select your preferred travel date to complete the booking.</p>
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
                                    <InputLabel value="Select Seat" class="text-lg font-medium mb-2" />
                                    <SecondaryButton type="button" @click="openModal" class="w-full py-3">{{ selectedSeatLabel }}</SecondaryButton>
                                    <InputError class="mt-2 text-red-500" :message="form.errors.seat" />
                                </div>

                                <div>
                                    <InputLabel value="Travel Date" class="text-lg font-medium mb-2" />
                                    <TextInput v-model="form.travel_date" type="date" class="w-full" />
                                    <InputError class="mt-2 text-red-500" :message="form.errors.travel_date" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                            <h1 class="text-4xl dark:text-gray-300 font-semibold">Payment Information</h1>
                            <div class="space-y-4">
                                <p class="text-lg text-red-600">Please make your payment to this GCash number and upload your receipt below.</p>
                            </div>
                            <div class="space-y-6">
                                <p class="text-xl font-medium text-gray-900 dark:text-gray-300">
                                    <strong>GCash Number:</strong> <span class="font-bold text-blue-600">0917-123-4567</span>
                                </p>

                                <div class="">
                                    <InputLabel value="Upload Payment Image" class="text-lg font-medium mb-2" />
                                    <file-pond
                                        name="payment_image"
                                        ref="pond"
                                        class-name="my-pond"
                                        label-idle="Drop files here..."
                                        allow-multiple="false"
                                        accepted-file-types="image/jpeg, image/png"
                                        :server="{
                                            url: '',
                                            process: {
                                                url: route('customer.payment-receipt-upload'),
                                                method: 'POST',
                                                onload: handleFilePondLoad
                                            },
                                            revert: handleFilePondRevert,
                                            headers: {
                                                'X-CSRF-TOKEN': $page.props.csrf_token
                                            }
                                        }
                                    "/>

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
                <h3 class="text-xl dark:text-gray-300 font-bold mb-2">Bus Seat Layout</h3>
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
                                            'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800': form.seat === seat,
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
                                            'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800': form.seat === seat,
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
