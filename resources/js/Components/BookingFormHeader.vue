<script setup>
import BusAvailabilityBadge from '@/Components/BusAvailabilityBadge.vue';

defineProps({
    busType: {
        type: String,
        required: true,
    },
    isBusAvailableNow: {
        type: Boolean,
        required: true,
    }
});
</script>

<template>
    <div class="flex gap-2 items-center">
        <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
            Booking Form for {{ busType }}
        </h2>
        <BusAvailabilityBadge :isBusAvailableNow="isBusAvailableNow" />
    </div>
</template>
