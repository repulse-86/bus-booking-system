<script setup>
import { Link } from '@inertiajs/vue3';
import { capitalize } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Paginator from '@/Components/Paginator.vue';

defineProps({
	bookings: {
		type: Object,
		required: true,
	},
	actionsVisible: {
		type: Boolean,
		default: false,
	},
	updateStatus: {
		type: Function,
		required: false,
	}
})
</script>

<template>
	<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
	    <thead class="bg-gray-50 dark:bg-gray-700">
	        <tr>
	            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">ID</th>
	            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Customer</th>
	            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Type</th>
	            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">From</th>
	            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">To</th>
	            <th class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Price</th>
	            <th v-if="actionsVisible" class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Actions</th>
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
	            <td v-if="actionsVisible" class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
	                <div class="flex space-x-2">
                        <PrimaryButton 
                            v-if="updateStatus" 
                            @click="updateStatus(booking.id, 'approved')">Approve</PrimaryButton>
                        <SecondaryButton 
                            v-if="updateStatus" 
                            @click="updateStatus(booking.id, 'declined')">Decline</SecondaryButton>
                    </div>
	            </td>
	        </tr>
	    </tbody>
	</table>
	<Paginator :object="bookings"/>
</template>