<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
	bookdId: {
		type: Number,
		required: true,
	},
	href: { // Changed back from routeName
		type: String,
		required: true,
	},
	value: {
		type: String,
		required: true,
	},
});
</script>

<template>
	<td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 break-words">
	    <Link :href="route(href, bookdId)"> <!-- Changed back to use href -->
	        {{ value }}
	    </Link>
	</td>
</template>