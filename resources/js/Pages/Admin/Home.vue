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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <RecordCountCard label="Registered Customers" :value="registeredUserCount"/>
                    <RecordCountCard label="Pending Bookings" :value="pendingBookingsCount"/>
                    <RecordCountCard label="Approved Bookings" :value="approvedBookingsCount"/>
                    <RecordCountCard label="Declined Bookings" :value="declinedBookingsCount"/>
                </div>

                <div class="lg:col-span-2">
                    <CumulativeAreaChart :cumulativePerMonthSales="cumulativePerMonthSales" :cumulativePerMonthUserCount="cumulativePerMonthUserCount" :cumulativePerMonthBookingCount="cumulativePerMonthBookingCount" class="mb-8"/>
                    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <DonutChart :chartData="bookingPerBusTypeData" label="Bookings"/>
                        <AreaChart :chartData="usersData" label="New Users"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
