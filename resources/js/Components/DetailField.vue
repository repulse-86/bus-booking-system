<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        required: true,
    }
});
</script>

<template>
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center text-left">
        <p class="text-lg font-black text-gray-700 dark:text-gray-300">{{ label }}:</p>
        <p class="text-xl text-gray-700 dark:text-gray-300">{{ value }}</p>
    </div>
</template>
