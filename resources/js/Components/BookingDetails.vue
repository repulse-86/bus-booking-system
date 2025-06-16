<script setup>
import { capitalize } from 'vue';
import BookingStatusBadge from '@/Components/BookingStatusBadge.vue';
import DetailField from '@/Components/DetailField.vue'; // Import the new component
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
            <DetailField label="Name" :value="capitalize(booking.customer.name)" />
            <DetailField label="Email" :value="capitalize(booking.customer.email)" />
            <DetailField label="Contact" :value="capitalize(booking.customer.mobile_number)" />
            <DetailField label="Bus Type" :value="booking.bus.bus_type" />
            <DetailField label="Date" :value="formatDate(booking.travel_date)" />
            <DetailField label="Departure Location" :value="capitalize(booking.bus.departure_location)" />
            <DetailField label="Destination Location" :value="capitalize(booking.bus.destination_location)" />
            <DetailField label="Seat/s" :value="seats.map(seat => seat.seat).join(', ')" />
            <DetailField label="Price" :value="`P${booking.total_price}`" />

            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
                <p class="text-lg font-black text-gray-700 dark:text-gray-300">Payment Status:</p>
                <div class="">
                    <BookingStatusBadge :status="booking.status"/>
                </div>
            </div>
        </div>
    </div>
</template>
