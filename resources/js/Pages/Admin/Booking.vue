<script setup>
import { capitalize } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingStatusBadge from '@/Components/BookingStatusBadge.vue';
import { formatDate } from '@/helpers';

const props = defineProps({
    booking: {
        type: Object,
        required: true,
    },
    seats: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <AppLayout :title="booking.customer.name">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                {{ booking.customer.name }}'s ticket for {{ booking.bus.bus_type }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <div class="space-y-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    <h1 class="text-3xl font-semibold text-left text-gray-800 dark:text-gray-200">Booking Details</h1>

                    <div class="space-y-6">
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Customer Name:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ capitalize(booking.customer.name ) }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Bus Type:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ booking.bus.bus_type }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Date:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ formatDate(booking.travel_date) }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Departure Location:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ capitalize(booking.bus.departure_location) }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Destination Location:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ capitalize(booking.bus.destination_location) }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Seat/s:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ seats.map(seat => seat.seat).join(", ") }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Price:</p>
                            <p class="text-xl text-gray-700 dark:text-gray-300">P{{ booking.total_price }}</p>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                            <p class="text-lg font-black text-gray-700 dark:text-gray-300">Payment Status:</p>
                            <div class="">
                                <BookingStatusBadge :status="booking.status"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="booking.reason_declined" class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg shadow-md border border-yellow-200 dark:border-yellow-700">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM9 7a1 1 0 112 0v3a1 1 0 11-2 0V7zm0 4a1 1 0 112 0v2a1 1 0 11-2 0v-2z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200">Reason for Decline</h3>
                    </div>
                    <p class="text-yellow-800 dark:text-yellow-300 mt-2">{{ booking.reason_declined }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
