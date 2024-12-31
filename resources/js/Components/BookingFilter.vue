<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    filterId: String,
    filterCustomerName: String,
});

const emit = defineEmits(['update:filterId', 'update:filterCustomerName']);

const localFilterId = ref(props.filterId);
const localFilterCustomerName = ref(props.filterCustomerName);

watch(localFilterId, (newValue) => {
    emit('update:filterId', newValue);
});

watch(localFilterCustomerName, (newValue) => {
    emit('update:filterCustomerName', newValue);
});
</script>

<template>
	<div class="max-w-7xl mx-auto mb-6">
	    <div class="flex items-center space-x-4">
	        <input 
	            v-model="localFilterId"
	            type="text" 
	            class="px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-200"
	            placeholder="Search bookings"
	        />
	        <input 
	            v-model="localFilterCustomerName"
	            type="text" 
	            class="px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-200"
	            placeholder="Search customer"
	        />
	    </div>
	</div>
</template>