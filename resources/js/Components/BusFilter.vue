<script setup>
import { ref, watch } from 'vue';

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
        <input v-model="localFilterBus" type="text" placeholder="Search bus..." class="p-4 border border-gray-300 rounded w-64" />
        <input v-model="localFilterTravelDate" type="datetime-local" class="p-4 border border-gray-300 rounded w-64" />
        <select v-model="localFilterDestinationLocation" class="p-4 border border-gray-300 rounded w-64">
            <option value="" selected>Select destination</option>
            <option v-for="destination in props.destinations" :key="destination" :value="destination">{{ destination }}</option>
        </select>
    </div>
</template>
