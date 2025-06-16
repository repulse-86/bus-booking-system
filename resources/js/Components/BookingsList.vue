<script setup>
import { ref, watch, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3'; // Link might be needed for the status badge
import { debounce } from 'lodash';
import Paginator from '@/Components/Paginator.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import THead from '@/Components/THead.vue';
import TCellBooking from '@/Components/TCellBooking.vue';
import BookingStatusBadge from '@/Components/BookingStatusBadge.vue';
import { formatDate, showAlert, showInputAlert, toast } from '@/helpers';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    bookings: {
        type: Object,
        required: true,
    },
    statusOptions: {
        type: Array,
        default: () => [
            { label: 'Pending', value: 'pending' },
            { label: 'Approved', value: 'approved' },
            { label: 'Declined', value: 'declined' },
        ]
    },
    headers: {
        type: Array,
        default: () => ['#', 'Customer', 'Type', 'Date', 'From', 'To', 'Price', 'Status']
    },
    adminView: { // To differentiate between admin and customer views for actions
        type: Boolean,
        default: false
    },
    currentRoute: { // To preserve filters on the correct route
        type: String,
        required: true,
    }
});

const form = useForm({});

const filterId = ref('');
const filterCustomerName = ref(''); // For admin view
const filterStatus = ref(''); // For customer view

// Watchers for filters
const watchDebounced = (filters) => {
    router.get(route(props.currentRoute), filters, { preserveState: true, preserveScroll: true });
};

watch([filterId, filterCustomerName, filterStatus], debounce(() => {
    let filters = {};
    if (props.adminView) {
        filters = { filterId: filterId.value, filterCustomerName: filterCustomerName.value };
        if (filterStatus.value) {
            filters.filterStatus = filterStatus.value;
        }
    } else {
        filters = { filterId: filterId.value, filterStatus: filterStatus.value };
    }
    watchDebounced(filters);
}, 500));


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
        }).then((result) => result.isConfirmed && result.value && updateBooking(result.value));
    } else {
        updateBooking();
    }
};

const actionsVisible = computed(() => props.adminView && props.currentRoute === 'admin.bookings.pendingBookings');

</script>

<template>
    <div>
        <div class="max-w-7xl mx-auto mb-6">
            <div class="flex flex-col sm:flex-row justify-start gap-4 mb-8">
                <TextInput v-model="filterId" placeholder="Search by Booking ID" class="w-full sm:w-auto"/>
                <template v-if="adminView">
                    <TextInput v-model="filterCustomerName" placeholder="Search by Customer Name" class="w-full sm:w-auto"/>
                    <SelectInput v-if="currentRoute === 'admin.bookings.index'" v-model="filterStatus" :options="statusOptions" selected="Status" class="w-full sm:w-auto"/>
                </template>
                <template v-else>
                    <SelectInput v-model="filterStatus" :options="statusOptions" selected="Status" class="w-full sm:w-auto"/>
                </template>
            </div>
        </div>
        <div class="overflow-x-auto max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <THead :headers="adminView ? headers : headers.filter(h => h !== 'Customer')" :actionsVisible="actionsVisible"/>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="booking in bookings.data" :key="booking.id" class="transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-600">
                        <TCellBooking :bookdId="booking.id" :href="adminView ? 'admin.bookings.show' : 'customer.bookings.show'" :value="booking.id.toString()"/>
                        <TCellBooking v-if="adminView" :bookdId="booking.id" :href="'admin.bookings.show'" :value="booking.customer.name"/>
                        <TCellBooking :bookdId="booking.id" :href="adminView ? 'admin.bookings.show' : 'customer.bookings.show'" :value="booking.bus.bus_type"/>
                        <TCellBooking :bookdId="booking.id" :href="adminView ? 'admin.bookings.show' : 'customer.bookings.show'" :value="formatDate(booking.travel_date)"/>
                        <TCellBooking :bookdId="booking.id" :href="adminView ? 'admin.bookings.show' : 'customer.bookings.show'" :value="booking.bus.departure_location.toString()"/>
                        <TCellBooking :bookdId="booking.id" :href="adminView ? 'admin.bookings.show' : 'customer.bookings.show'" :value="booking.bus.destination_location.toString()"/>
                        <TCellBooking :bookdId="booking.id" :href="adminView ? 'admin.bookings.show' : 'customer.bookings.show'" :value="`P ${booking.total_price}`" />
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                            <BookingStatusBadge :status="booking.status"/>
                        </td>
                        <td v-if="actionsVisible" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button @click="updateBookingStatus(booking.id, 'approved')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600">Approve</button>
                            <button @click="updateBookingStatus(booking.id, 'declined')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Decline</button>
                        </td>
                    </tr>
                    <tr v-if="bookings.data.length === 0">
                        <td :colspan="headers.length + (actionsVisible ? 1 : 0)" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No bookings found.
                        </td>
                    </tr>
                </tbody>
            </table>
            <Paginator :object="bookings"/>
        </div>
    </div>
</template>
