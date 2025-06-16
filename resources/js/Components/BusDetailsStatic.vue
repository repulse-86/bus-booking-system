<script setup>
import Label from '@/Components/Label.vue'; // Assuming Label.vue is a simple text label

defineProps({
    bus: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
        <div class="space-y-6 flex flex-col justify-center">
            <div>
                <Label>Departure Location</Label>
                <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.departure_location }}</p>
            </div>
            <div>
                <Label>Destination Location</Label>
                <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.destination_location }}</p>
            </div>
        </div>
        <div class="space-y-6 flex flex-col justify-center">
            <div>
                <Label>Number of Seats</Label>
                <p class="text-lg text-gray-800 dark:text-gray-300">{{ bus.available_seats }}</p>
            </div>
            <div>
                <Label>Price Per Ticket</Label>
                <p class="text-lg text-gray-800 dark:text-gray-300">P {{ bus.price_per_ticket }}</p>
            </div>
        </div>
    </div>
</template>
