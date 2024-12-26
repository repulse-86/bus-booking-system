<script setup>
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import BusCard from '@/Components/BusCard.vue';

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

const filterBus = ref('');
const filterTravelDate = ref('');
const filterDestinationLocation = ref('');

const watchDebounced = (field, value) => {
    router.get(route('customer.home'), {
            filterBus: filterBus.value,
            filterTravelDate: filterTravelDate.value,
            filterDestinationLocation: filterDestinationLocation.value,
        }, 
        { preserveState: true }
    );
};

watch(filterBus, debounce((newValue) => watchDebounced('filterBus', newValue), 500));
watch(filterTravelDate, debounce((newValue) => watchDebounced('filterTravelDate', newValue), 500));
watch(filterDestinationLocation, debounce((newValue) => watchDebounced('filterDestinationLocation', newValue), 500));

</script>

<template>
    <Head title="Home" />
    <div class="text-center my-8">
        <h1 class="text-4xl font-semibold">Find Your Perfect Bus Ride</h1>
    </div>

    <!-- Dropdowns Section -->
    <div class="flex justify-center gap-8 mb-8">
        <input v-model="filterBus" type="text" placeholder="Search bus.." class="p-4 border border-gray-300 rounded w-64" />
        <input v-model="filterTravelDate" type="datetime-local" class="p-4 border border-gray-300 rounded w-64" />
        <select v-model="filterDestinationLocation" class="p-4 border border-gray-300 rounded w-64">
            <option value="" selected>Select destination</option>
            <option v-for="destination in destinations" :key="destination" :value="destination">{{ destination }}</option>
        </select>
    </div>

    <!-- Bus Info Cards -->
    <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
        <Link v-for="(bus, index) in buses" :key="index" :href="route('booked-tickets.create', bus.id)">
            <BusCard :bus="bus"/>
        </Link>
    </div>

    <!-- Footer Section -->
    <footer class="py-16 text-center text-sm text-black dark:text-white/70">
        Online Bus Ticketing System
    </footer>
</template>
