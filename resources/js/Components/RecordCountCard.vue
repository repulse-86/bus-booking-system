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
    <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 flex items-center justify-between  mb-6">
        <h1 class="text-4xl font-black">{{ label }}</h1>
        <p class="text-9xl text-green-600 text-end">{{ formatNumber(value) }}</p>
    </div>
</template>