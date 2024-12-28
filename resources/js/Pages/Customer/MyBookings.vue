<script setup>
import { capitalize } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Paginator from '@/Components/Paginator.vue';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <AppLayout title="My Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Bookings
            </h2>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Type</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">From</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">To</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Price</th>
                            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(booking, index) in bookings.data" :key="booking.index">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">{{ capitalize(booking.bus.bus_type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">{{ capitalize(booking.bus.departure_location) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">{{ capitalize(booking.bus.destination_location) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">P{{ booking.bus.price_per_ticket }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                <span 
                                    :class="{
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300': booking.status === 'pending',
                                        'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300': booking.status === 'approved',
                                    }"
                                    class="inline-block px-3 py-1 text-xs font-semibold rounded-full">
                                    {{ capitalize(booking.status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Paginator :object="bookings"/>
            </div>
        </div>
    </AppLayout>
</template>
