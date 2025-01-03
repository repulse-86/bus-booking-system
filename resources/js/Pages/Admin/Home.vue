<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AreaChart from '@/Components/AreaChart.vue';

defineProps({
    usersData: Object,
});

</script>

<template>
    <AppLayout title="Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <AreaChart :chartData="usersData" label="New Users"/>
            </div>
        </div>
    </AppLayout>
</template>
