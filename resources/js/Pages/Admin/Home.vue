<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AreaChart from '@/Components/AreaChart.vue';
import DonutChart from '@/Components/DonutChart.vue';
import RecordCountCard from '@/Components/RecordCountCard.vue';
import CumulativeAreaChart from '@/Components/CumulativeAreaChart.vue';

defineProps({
    usersData: Object,
    bookingPerBusTypeData: Object,
    registeredUserCount: Number,
    pendingBookingsCount: Number,
    approvedBookingsCount: Number,
    declinedBookingsCount: Number,
    cumulativePerMonthSales: Array,
    cumulativePerMonthBookingCount: Array,
    cumulativePerMonthUserCount: Array,
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
            <div class="max-w-7xl mx-auto sm:px-6 grid grid-cols-1 lg:grid-cols-3 lg:px-8 gap-6">
                <div class="grid grid-cols-1 lg:col-span-1 gap-6 ">
                    <RecordCountCard label="Registered Customers" :value="registeredUserCount"/>
                    <RecordCountCard label="Pending Bookings" :value="pendingBookingsCount"/>
                    <RecordCountCard label="Approved Bookings" :value="approvedBookingsCount"/>
                    <RecordCountCard label="Declined Bookings" :value="declinedBookingsCount"/>
                </div>

                <div class="grid grid-cols-1 lg:col-span-2 sm:col-span-2 gap-6 ">
                    <CumulativeAreaChart :cumulativePerMonthSales="cumulativePerMonthSales" :cumulativePerMonthUserCount="cumulativePerMonthUserCount" :cumulativePerMonthBookingCount="cumulativePerMonthBookingCount"/>
                    <div class="grid lg:grid-cols-2 sm:grid-cols-1 gap-6 overflow-auto">
                        <DonutChart :chartData="bookingPerBusTypeData" label="Bookings"/>
                        <AreaChart :chartData="usersData" label="New Users"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
