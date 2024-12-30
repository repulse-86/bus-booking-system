<script setup>
import { ref, watch, capitalize } from 'vue';
import { debounce } from 'lodash';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Paginator from '@/Components/Paginator.vue';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});

const filterId = ref('');
const filterCustomerName = ref('');

const watchDebounced = (field, value) => {
    router.get(route('admin.booked-tickets.approvedBookings'), {
        filterId: filterId.value,
        filterCustomerName: filterCustomerName.value,
    }, { preserveState: true });
};

watch(filterId, debounce((newValue) => watchDebounced('filterId', newValue), 500));
watch(filterCustomerName, debounce((newValue) => watchDebounced('filterCustomerName', newValue), 500));
</script>

<template>
    <AppLayout title="Declined Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                Declined Bookings
            </h2>
        </template>
        <div class="py-12">
            <!-- Filters Section -->
            <div class="max-w-7xl mx-auto mb-6">
                <div class="flex items-center space-x-4">
                    <!-- Search Box -->
                    <input 
                        v-model="filterId"
                        type="text" 
                        class="px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-200"
                        placeholder="Search bookings"
                    />
                    <input 
                        v-model="filterCustomerName"
                        type="text" 
                        class="px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-200"
                        placeholder="Search customer"
                    />
                </div>
            </div>
            <div class="overflow-x-auto max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">ID</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Customer</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Type</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">From</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">To</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Price</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(booking, index) in bookings.data" :key="booking.index">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
                                <Link :href="route('admin.booked-tickets.show', booking.id)">
                                    {{ booking.id }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
                                <Link :href="route('admin.booked-tickets.show', booking.id)">
                                    {{ capitalize(booking.customer.name) }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
                                <Link :href="route('admin.booked-tickets.show', booking.id)">
                                    {{ capitalize(booking.bus.bus_type) }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
                                <Link :href="route('admin.booked-tickets.show', booking.id)">
                                    {{ capitalize(booking.bus.departure_location) }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
                                <Link :href="route('admin.booked-tickets.show', booking.id)">
                                    {{ capitalize(booking.bus.destination_location) }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
                                <Link :href="route('admin.booked-tickets.show', booking.id)">
                                    P{{ booking.bus.price_per_ticket }}
                                </Link>
                            </td>
                        </tr>
                    </tbody>

                </table>
                <Paginator :object="bookings"/>
            </div>
        </div>
    </AppLayout>
</template>
