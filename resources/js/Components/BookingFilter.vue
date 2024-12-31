<script setup>
import { ref, watch } from 'vue';
import TextInput from '@/Components/TextInput.vue';

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
	    	<TextInput v-model="localFilterId" placeholder="Search bookings"/>
	    	<TextInput v-model="localFilterCustomerName" placeholder="Search customer"/>
	    </div>
	</div>
</template>