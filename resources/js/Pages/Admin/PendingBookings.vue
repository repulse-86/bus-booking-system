<script setup>
import { router } from '@inertiajs/vue3';
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

const updateBookingStatus = (bookingId, status) => {
    router.put(
            route('admin.booked-tickets.update', {
                bookedTicket: bookingId, 
                status: status
            }), 
    {
        onSuccess: () => {
            alert('Booking approved!');
        },
        onError: (error) => {
            alert('Action failed!');
            console.error(error);
        }
    });
};

useDebouncedFilters('admin.booked-tickets.pendingBookings');
</script>

<template>
    <AppLayout title="Pending Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                Pending Bookings
            </h2>
        </template>
        <div class="py-12">
            <BookingFilter v-model:filterId="filterId" v-model:filterCustomerName="filterCustomerName"/>
            <div class="overflow-x-auto max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <BookingsTable :bookings="bookings" :actionsVisible="true" :updateStatus="updateBookingStatus"/>
            </div>
        </div>
    </AppLayout>
</template>
