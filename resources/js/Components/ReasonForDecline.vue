<script setup>
defineProps({
    reason: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div v-if="reason" class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg shadow-md border border-yellow-200 dark:border-yellow-700">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM9 7a1 1 0 112 0v3a1 1 0 11-2 0V7zm0 4a1 1 0 112 0v2a1 1 0 11-2 0v-2z" clip-rule="evenodd" />
            </svg>
            <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200">Reason for Decline</h3>
        </div>
        <p class="text-yellow-800 dark:text-yellow-300 mt-2">{{ reason }}</p>
    </div>
</template>
