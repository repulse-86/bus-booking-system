<script setup>
import { computed, capitalize } from 'vue';

const props = defineProps({
	status: String,
});

const statusClasses = computed(() => {
	switch (props.status) {
	case 'pending':
		return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300';
	case 'approved':
		return 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300';
	case 'declined':
		return 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300';
	default:
		return '';
	}
});
</script>

<template>
	<span :class="[statusClasses, 'inline-block px-3 py-1 text-xs font-semibold rounded-full']">
		{{ capitalize(status) }}
	</span>
</template>
