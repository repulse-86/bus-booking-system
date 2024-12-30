<script setup>
import { capitalize } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    bookedTicket: {
        type: Object,
        required: true,
    }
});

const imageSrc = `../../files/payments/${props.bookedTicket.payment_image}.png`;
</script>

<template>
    <AppLayout :title="bookedTicket.customer.name">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                {{ bookedTicket.customer.name }}'s ticket for {{ bookedTicket.bus.bus_type }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Ticket Details -->
                <div>
                    <div class="space-y-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                        <h1 class="text-3xl font-semibold text-left text-gray-800 dark:text-gray-200">Booked Ticket Details</h1>

                        <div class="space-y-6">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Customer Name:</p>
                                <p class="text-xl text-gray-700">{{ capitalize(bookedTicket.customer.name ) }}</p>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Bus Type:</p>
                                <p class="text-xl text-gray-700">{{ bookedTicket.bus.bus_type }}</p>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Seat Number:</p>
                                <p class="text-xl text-gray-700">{{ bookedTicket.seat }}</p>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Departure Location:</p>
                                <p class="text-xl text-gray-700">{{ capitalize(bookedTicket.bus.departure_location) }}</p>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Destination Location:</p>
                                <p class="text-xl text-gray-700">{{ capitalize(bookedTicket.bus.destination_location) }}</p>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Price:</p>
                                <p class="text-xl text-gray-700">P{{ bookedTicket.bus.price_per_ticket }}</p>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                                <p class="text-lg font-black text-gray-700">Payment Status:</p>
                                <div class="">
                                    <span 
                                        :class="bookedTicket.status === 'pending' 
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300' 
                                            : bookedTicket.status === 'approved' 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' 
                                            : ''"
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full min-w-max">
                                        {{ capitalize(bookedTicket.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment Receipt -->
                <div class="space-y-6 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-medium text-gray-700">Payment Receipt</h2>
                    <div class="flex justify-center">
                        <img :src="imageSrc" alt="Payment Receipt" class="w-full max-w-sm object-contain">
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
