<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Buses from '@/Components/Buses.vue';

const props = defineProps({
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
        <div class="relative flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl lg:max-w-7xl">
                <Buses :destinations="destinations" :buses="buses"/>
            </div>
        </div>
    </AppLayout>
</template>
