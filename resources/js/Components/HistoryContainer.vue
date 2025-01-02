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
	        <p class="mb-2">{{ formatDate(booking.travel_date) }}</p>
	        <div class="w-full flex justify-between mb-4">
	            <div>
	                <h1 class="text-3xl font-black text-blue-600">{{ booking.bus.departure_location }}</h1>
	                <p class="text-slate-500">Departure location</p>
	            </div>
	            <div>
	                <h1 class="text-3xl font-black text-blue-600 text-end">{{ booking.bus.destination_location }}</h1>
	                <p class="text-slate-500">Destination location</p>
	            </div>
	        </div>
	        
	        <div class="text-2xl font-bold text-zinc-600">P {{ booking.bus.price_per_ticket }}</div>
	    </div>
	    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
	</div>
</template>