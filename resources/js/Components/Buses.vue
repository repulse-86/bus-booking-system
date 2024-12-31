<script setup>
import { Head, Link } from '@inertiajs/vue3';
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
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

useDebouncedFilters('customer.home');

</script>

<template>
    <Head title="Home" />
    <div class="text-center my-8">
        <h1 class="text-4xl font-semibold">Find Your Perfect Bus Ride</h1>
    </div>

    <BusFilter
        v-model:filterBus="filterBus"
        v-model:filterTravelDate="filterTravelDate"
        v-model:filterDestinationLocation="filterDestinationLocation"
        :destinations="destinations"
    />

    <!-- Bus Info Cards -->
    <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
        <Link v-for="(bus, index) in buses" :key="index" :href="route('customer.booked-tickets.create', bus.id)">
            <BusCard :bus="bus"/>
        </Link>
    </div>

    <!-- Footer Section -->
    <footer class="py-16 text-center text-sm text-black dark:text-white/70">
        Online Bus Ticketing System
    </footer>
</template>
