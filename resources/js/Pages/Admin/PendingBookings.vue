<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingsList from '@/Components/BookingsList.vue';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});

</script>

<template>
    <AppLayout title="Pending Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                Pending Bookings
            </h2>
        </template>
        <div class="py-12">
            <BookingsList :bookings="bookings" :adminView="true" currentRoute="admin.bookings.pendingBookings"/>
        </div>
    </AppLayout>
</template>
