<script setup>
import { defineProps, ref, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useDebouncedFilters, filterBus, filterTravelDate, filterDestinationLocation } from '@/Utilities/useBusFilter.js';
import BusCard from '@/Components/BusCard.vue';
import BusFilter from '@/Components/BusFilter.vue';

const props = defineProps({
    destinations: {
        type: Array,
        required: true,
    },
    buses: {
        type: Array,
        required: true,
    },
    routeFilter: {
        type: String,
        required: true,
    },
});

useDebouncedFilters(props.routeFilter);


const isLgScreen = ref(false); // Flag to track large screen visibility

const checkScreenSize = () => {
    isLgScreen.value = window.innerWidth >= 800; // or another breakpoint for lg screen size
};

onMounted(() => {
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize); // Listen for window resize
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', checkScreenSize); // Clean up event listener
});

</script>

<template>
    <BusFilter
        v-model:filterBus="filterBus"
        v-model:filterTravelDate="filterTravelDate"
        v-model:filterDestinationLocation="filterDestinationLocation"
        :destinations="destinations"
    />

    <!-- Conditional Rendering for Bus Info Cards -->
    <div v-if="buses.length > 0">
        <!-- For LG screens -->
        <div 
            v-if="isLgScreen"
            class="grid grid-cols-3 gap-8 flex-grow"
            :class="{'min-h-[200px]': buses.length === 0 }"
        >
            <Link v-for="(bus, index) in buses" :key="index" :href="route('customer.bookings.create', bus.id)">
                <BusCard :bus="bus"/>
            </Link>
        </div>

        <!-- For SM screens -->
        <div 
            v-else
            class="sm:grid flex-grow"
            :class="{
                'min-h-[50px]': buses.length === 0,
                'grid-cols-1 overflow-x-auto': buses.length > 0
            }"
        >
            <div class="flex gap-4">
                <Link v-for="(bus, index) in buses" :key="index" :href="route('customer.bookings.create', bus.id)" class="shrink-0 w-9/12">
                    <BusCard :bus="bus"/>
                </Link>
            </div>
        </div>
    </div>

    <!-- Fallback message when no buses available -->
    <div v-else>
        <p class="text-center text-gray-500 dark:text-gray-400 col-span-full">
            No buses available for the selected criteria.
        </p>
    </div>
</template>
