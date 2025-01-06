<script setup>
defineProps({
	bus: {
		type: Object,
		required: true,
	}
});
</script>

<template>
	<div class="max-w-sm mx-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden">
	    <!-- Image Section -->
	    <img 
	        :src="'../../../' + bus.bus_type + '.png'" 
	        alt="Bus Image" 
	        class="w-full h-64 object-cover"
	    />

	    <!-- Content Section -->
	    <div class="p-6">
	        <!-- Header Section -->
	        <div class="mb-4">
	            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-300 tracking-wide">{{ bus.bus_type }}</h3>
	        </div>

	        <!-- Details Section -->
	        <div class="space-y-4 ">
	        	<div>
	                <p class="text-sm text-gray-500 dark:text-gray-500 uppercase tracking-wide">Departure Location</p>
	                <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.departure_location }}</p>
	            </div>

	            <div>
	                <p class="text-sm text-gray-500 dark:text-gray-500 uppercase tracking-wide">Destination Location</p>
	                <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.destination_location }}</p>
	            </div>

	            <div>
	                <p class="text-sm text-gray-500 dark:text-gray-500 uppercase tracking-wide">Time Available</p>
	                <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.time_available_start }} - {{ bus.time_available_end }}</p>
	            </div>

	            <!-- Price and Seats -->
	            <div>
	                <p class="text-sm text-gray-500 dark:text-gray-500 uppercase tracking-wide">Price Per Ticket</p>
	                <p class="text-lg text-gray-800 dark:text-gray-300">P {{ bus.price_per_ticket }}</p>
	            </div>
	        </div>
	    </div>
	</div>

</template>
