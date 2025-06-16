<script setup>
import { capitalize } from 'vue';
import BookingStatusBadge from '@/Components/BookingStatusBadge.vue';
import { formatDate } from '@/helpers';

defineProps({
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
    <div class="space-y-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-left text-gray-800 dark:text-gray-200">Booking Details</h1>

        <div class="space-y-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                <p class="text-lg font-black text-gray-700 dark:text-gray-300">Name:</p>
                <p class="text-xl text-gray-700 dark:text-gray-300">{{ capitalize(booking.customer.name ) }}</p>
            </div>
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                <p class="text-lg font-black text-gray-700 dark:text-gray-300">Email:</p>
                <p class="text-xl text-gray-700 dark:text-gray-300">{{ capitalize(booking.customer.email ) }}</p>
            </div>
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                <p class="text-lg font-black text-gray-700 dark:text-gray-300">Contact:</p>
                <p class="text-xl text-gray-700 dark:text-gray-300">{{ capitalize(booking.customer.mobile_number ) }}</p>
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
</template>
