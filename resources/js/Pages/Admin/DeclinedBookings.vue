<script setup>
import { useDebouncedFilters, filterId, filterCustomerName } from '@/Utilities/useBookingFilter.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingFilter from '@/Components/BookingFilter.vue';
import BookingsTable from '@/Components/BookingsTable.vue';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});
useDebouncedFilters('admin.booked-tickets.declinedBookings');
</script>
<template>
    <AppLayout title="Declined Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                Declined Bookings
            </h2>
        </template>
        <div class="py-12">
            <BookingFilter v-model:filterId="filterId" v-model:filterCustomerName="filterCustomerName"/>
            <div class="overflow-x-auto max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <BookingsTable :bookings="bookings"/>
            </div>
        </div>
    </AppLayout>
</template>
