<script setup>
	defineProps({
		headers: {
			type: Array,
			required: true,
		},
		actionsVisible: {
			type: Boolean,
			default: false
		}
	});
</script>

<template>
	<tr>
		<th v-for="(header, index) in headers" :key="index" class="px-6 py-4 text-left font-extrabold text-lg text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">
			{{ header }}
		</th>
		<th v-if="actionsVisible" class="px-6 py-3 text-left font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/5">Actions</th>
	</tr>
</template>