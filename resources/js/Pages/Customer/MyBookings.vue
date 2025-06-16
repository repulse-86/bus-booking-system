<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingsList from '@/Components/BookingsList.vue';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});

const customerHeaders = ['#', 'Type', 'Date', 'From', 'To', 'Price', 'Status'];

</script>

<template>
    <AppLayout title="My Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Bookings
            </h2>
        </template>

        <div class="py-12">
            <BookingsList
                :bookings="bookings"
                :adminView="false"
                :headers="customerHeaders"
                currentRoute="customer.bookings.index"
            />
        </div>
    </AppLayout>
</template>
