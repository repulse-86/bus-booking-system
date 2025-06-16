<script setup>
defineProps({
    isBusAvailableNow: {
        type: Boolean,
        required: true,
    }
});
</script>

<template>
    <span
        v-if="!isBusAvailableNow"
        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100"
    >
        Unavailable
    </span>
    <span
        v-else
        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100"
    >
        Available
    </span>
</template>
