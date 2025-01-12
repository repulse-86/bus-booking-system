<script setup>
import { ref, watch } from 'vue';
import TextInput from './TextInput.vue';
import SelectInput from './SelectInput.vue';

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
    <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-8 mb-8">
        <TextInput 
            v-model="localFilterBus" 
            placeholder="Search bus..." 
            class="w-full sm:w-64"
        />
        <TextInput 
            v-model="localFilterTravelDate" 
            type="date" 
            placeholder="Search bus..." 
            class="w-full sm:w-64"
        />
        <SelectInput 
            v-model="localFilterDestinationLocation" 
            :options="destinations" 
            selected="Select destination" 
            class="w-full sm:w-64"
        />
    </div>
</template>

