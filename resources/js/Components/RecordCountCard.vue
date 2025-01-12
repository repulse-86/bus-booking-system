<script setup>
import { formatNumber } from '@/helpers';

defineProps({
	label: {
		type: String,
		required: true,
	},
	value: {
		type: Number,
		required: true,
	}
});
</script>

<template>
    <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 flex items-center justify-between">
        <h1 class="lg:text-4xl md:text-4xl font-bold text-gray-800 dark:text-gray-300">{{ label }}</h1>
        <p class="lg:text-8xl md:text-8xl text-green-500 text-end">{{ formatNumber(value) }}</p>
    </div>
</template>