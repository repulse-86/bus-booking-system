<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Buses from '@/Components/Buses.vue';

defineProps({
    destinations: {
        type: Array,
        required: true,
    },
    buses: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <AppLayout title="Browse Buses">
        <div class="relative flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white flex-grow">
            <div class="w-full lg:max-w-7xl">
                <div class="text-center my-8 space-y-10">
                    <h1 class="text-4xl">Find Your Perfect Bus Ride</h1>
                </div>
                
                <Buses :destinations="destinations" :buses="buses" route-filter="customer.home"/>

                <!-- Footer Section -->
                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Online Bus Ticketing System
                </footer>
            </div>
        </div>
    </AppLayout>
</template>
