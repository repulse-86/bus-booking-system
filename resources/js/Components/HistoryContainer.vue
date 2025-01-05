<script setup>
import { formatDate } from '@/helpers';

defineProps({
	booking: Object,
	required: true,
});
</script>

<template>
	<div>
	    <div class="p-8">
	        <div class="w-full flex justify-between items-center mb-4">
	            <h1 class="text-2xl font-black text-gray-800 dark:text-gray-300">{{ booking.bus.destination_location }}</h1>
	            <p class="text-gray-500 dark:text-gray-300">{{ formatDate(booking.travel_date) }}</p>
	        </div>
	        <div class="text-xl text-gray-600 dark:text-gray-300">P {{ booking.bus.price_per_ticket }}</div>
	    </div>
	    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
	</div>
</template>