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
    const handleResponse = (isSuccess, action) => {
        const messages = {
            approved: {
                success: 'Booking ticket approved successfully! An email has been sent to the customer.',
                error: 'Ticket Approval Failed!',
            },
            declined: {
                success: 'Booking ticket declined successfully! An email has been sent to the customer.',
                error: 'Ticket Decline Failed!',
            },
        };

        if (isSuccess) {
            toast(messages[action].success);
        } else {
            showAlert({
                icon: 'error',
                title: messages[action].error,
                text: 'There was an issue processing your request. Please try again.',
            });
        }
    };

    const updateBooking = (reason = '') => {
        const data = { booking: bookingId, status, ...(reason && { reason }) };

        form.put(route('admin.bookings.update', data), {
            onSuccess: () => handleResponse(true, status),
            onError: () => handleResponse(false, status),
        });
    };

    if (status === 'declined') {
        showInputAlert({
            title: 'Provide a reason for declining the booking',
            text: 'This helps track declined bookings.',
            placeholder: 'Enter reason here...',
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
        }).then((result) => result.isConfirmed && updateBooking(result.value));
    } else {
        updateBooking();
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
