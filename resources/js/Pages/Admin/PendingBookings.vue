<script setup>
import { useForm } from '@inertiajs/vue3';
import { useDebouncedFilters, filterId, filterCustomerName } from '@/Utilities/useBookingFilter.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingFilter from '@/Components/BookingFilter.vue';
import BookingsTable from '@/Components/BookingsTable.vue';
import { showAlert, showInputAlert, toast } from '@/helpers';

const form = useForm({});

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});

const updateBookingStatus = (bookingId, status) => {
    const updateBooking = (status, reason = null) => {
        const data = { booking: bookingId, status: status };
        if (reason) data.reason = reason;

        form.put(route('admin.bookings.update', data), {
            onSuccess: () => {
                const message = status === 'approved'
                    ? 'Booking ticket approved successfully! An email has been sent to the customer.'
                    : 'Booking ticket declined successfully! An email has been sent to the customer.';
                toast(message);
            },
            onError: () => {
                const message = status === 'approved'
                    ? 'Ticket Approval Failed!'
                    : 'Ticket Decline Failed!';
                showAlert({
                    icon: 'error',
                    title: message,
                    text: 'There was an issue with your approval. Please try again.',
                });
            }
        });
    };

    if (status === 'declined') {
        showInputAlert({
            title: 'Please provide a reason for declining the booking',
            text: 'Providing a reason helps track the decisions made for declined bookings.',
            placeholder: 'Enter reason here...',
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                updateBooking(status, result.value);
            }
        });
    } else {
        updateBooking(status);
    }
};


useDebouncedFilters('admin.bookings.pendingBookings');
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
