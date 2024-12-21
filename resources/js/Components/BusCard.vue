<script setup>
defineProps({
	bus: {
		type: Object,
		required: true,
	}
});
</script>

<template>
	<div class="max-w-sm mx-auto bg-white border border-gray-300 rounded-lg shadow-lg">
		<!-- Image Section -->
		<img 
			:src="bus.bus_type === 'Economy' ? '../../../luxury.png' : '../../../economy.png'" 
			alt="Bus Image" 
			class="w-full h-64 object-cover rounded-t-lg"
		/>

		<!-- Text Section with padding -->
		<div class="p-4 space-y-4">
			<h3 class="text-2xl font-semibold text-center text-blue-600">{{ bus.bus_type }}</h3>
			<div class="space-y-2 text-gray-700">
				<p class="text-lg font-medium"><strong>Departure Location:</strong> {{ bus.departure_location }}</p>
				<p class="text-lg font-medium"><strong>Destination Location:</strong> {{ bus.destination_location }}</p>
				<p class="text-lg font-medium"><strong>Time Available:</strong> {{ bus.time_available_start }} - {{ bus.time_available_end }}</p>
			</div>
			<div class="space-y-2 mt-4">
				<p class="text-xl font-semibold"><strong>Price Per Ticket:</strong> {{ bus.price_per_ticket }}</p>
				<p class="text-xl font-semibold"><strong>Available Seats:</strong> {{ bus.available_seats }}</p>
			</div>
		</div>
	</div>
</template>
