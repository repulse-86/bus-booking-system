<script setup>
defineProps({
	title: String,
	subTitle: String,
})
</script>

<template>
	<div class="mb-6">
	    <h1 class="text-3xl text-gray-800 dark:text-gray-300 font-bold">{{ title }}</h1>
	    <p class="text-lg text-gray-500 dark:text-gray-400">{{ subTitle }}</p>
	</div>
</template>