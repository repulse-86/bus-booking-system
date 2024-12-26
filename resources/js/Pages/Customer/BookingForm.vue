<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { auth } = usePage().props;
const htmlForm = ref(null);

const props = defineProps({
    bus: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    bus_id: props.bus.id,
    customer_id: auth.user.id,
    seat: null,
    payment_image: null,
});

const seatNumbers = ref([]);
for (let i = 1; i <= props.bus.available_seats; i++) {
    seatNumbers.value.push({ label: `Seat ${i}`, value: i });
}

const submitForm = () => {
    console.log(form);
    form.post(route('booked-tickets.store'), {
        onSuccess: () => {
            alert('success');
            resetForm();
        },
        onError: () => {
            alert('error');
        }
    });
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    console.log('File selected:', file);
    form.payment_image = file;
};

const resetFileInput = () => {
    if (htmlForm.value) {
        htmlForm.value.reset();
        htmlForm.value.querySelector('input[type="file"]').value = '';
    }
};

const resetForm = () => {
    form.reset();
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
                        <h1 class="text-4xl font-semibold mb-6 text-left">Booking Details</h1>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="font-bold text-2xl text-gray-700">Departure Location</label>
                                    <p class="text-xl text-gray-900 italic">{{ bus.departure_location }}</p>
                                </div>
                                <div>
                                    <label class="font-bold text-2xl text-gray-700">Destination Location</label>
                                    <p class="text-xl text-gray-900 italic">{{ bus.destination_location }}</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="font-bold text-2xl text-gray-700">Available Seats</label>
                                    <p class="text-xl text-gray-900 italic">{{ bus.available_seats }}</p>
                                </div>
                                <div>
                                    <label class="font-bold text-2xl text-gray-700">Price Per Ticket</label>
                                    <p class="text-xl text-gray-900 italic">P{{ bus.price_per_ticket }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <InputLabel value="Select Seat" class="text-lg font-medium  mb-2"/>
                            <SelectInput v-model="form.seat" :options="seatNumbers" class="w-full"/>
                            <InputError class="mt-2 text-red-500" :message="form.errors.seat" />
                        </div>
                    </div>

                    <!-- Right Column (Payment Details) -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full space-y-6">
                        <h1 class="text-4xl font-semibold text-left">Payment Information</h1>

                        <div class="space-y-4">
                            <p class="text-lg text-red-600">Please make your payment to this GCash number and upload your receipt below.</p>
                            <p class="text-xl font-medium text-gray-900 mb-4">
                                <strong>GCash Number:</strong> <span class="font-bold text-blue-600">0917-123-4567</span>
                            </p>
                        </div>

                        <div class="mb-6">
                            <InputLabel value="Upload Payment Image" class="text-lg font-medium mb-2"/>
                            <TextInput 
                                type="file"
                                class="mt-1 w-full py-2 px-4 border rounded-lg"
                                @change="handleFileChange"
                                :message="form.errors.file" />
                            <InputError class="mt-2 text-red-500" :message="form.errors.file" />
                        </div>

                        <div class="mt-6">
                            <PrimaryButton class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Submit
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
