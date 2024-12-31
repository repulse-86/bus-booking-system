<script setup>
import { ref, watch } from 'vue';
import TextInput from './TextInput.vue';

const props = defineProps({
    filterBus: String,
    filterTravelDate: String,
    filterDestinationLocation: String,
    destinations: {
        type: Array,
        required: true
    },
});

const emit = defineEmits(['update:filterBus', 'update:filterTravelDate', 'update:filterDestinationLocation']);

const localFilterBus = ref(props.filterBus);
const localFilterTravelDate = ref(props.filterTravelDate);
const localFilterDestinationLocation = ref(props.filterDestinationLocation);

watch(localFilterBus, (newValue) => {
    emit('update:filterBus', newValue);
});

watch(localFilterTravelDate, (newValue) => {
    emit('update:filterTravelDate', newValue);
});

watch(localFilterDestinationLocation, (newValue) => {
    emit('update:filterDestinationLocation', newValue);
});
</script>

<template>
    <div class="flex justify-center gap-8 mb-8">
        <TextInput v-model="localFilterBus" placeholder="Search bus..." class="w-64"/>
        <TextInput v-model="localFilterTravelDate" type="datetime-local" placeholder="Search bus..." class="w-64"/>
        <select v-model="localFilterDestinationLocation" class="p-4 border border-gray-300 rounded w-64">
            <option value="" selected>Select destination</option>
            <option v-for="destination in props.destinations" :key="destination" :value="destination">{{ destination }}</option>
        </select>
    </div>
</template>
