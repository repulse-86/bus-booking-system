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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="dark:bg-gray-800 overflow-hidden sm:rounded-lg">
                    <Buses :destinations="destinations" :buses="buses"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
