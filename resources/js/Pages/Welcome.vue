<script setup>
import { defineProps } from 'vue';
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

useDebouncedFilters('home');
</script>

<template>
    <Head title="Bus Search" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="relative flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                
                <!-- Header Section -->
                <header class="flex justify-between items-center py-4">
                    <div class="flex-1"></div>
                    <nav v-if="canLogin" class="flex items-center space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="text-black dark:text-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-black dark:text-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-black dark:text-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <!-- Tagline Section -->
                <div class="text-center my-4">
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
                    <BusCard v-for="(bus, index) in buses" :key="index" :bus="bus"/>
                </div>

                <!-- Footer Section -->
                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Online Bus Ticketing System
                </footer>
            </div>
        </div>
    </div>
</template>
