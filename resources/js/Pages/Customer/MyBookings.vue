<script setup>
import { onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingsList from '@/Components/BookingsList.vue';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});

const customerHeaders = ['#', 'Type', 'Date', 'From', 'To', 'Price', 'Status'];

const page = usePage();

const channelName = () => {
    const userId = page.props.auth?.user?.id;

    return userId ? `booking.update.${userId}` : null;
};

const refreshBookings = () => {
    router.reload({ only: ['bookings'] });
};

onMounted(() => {
    const channel = channelName();

    if (!window.Echo || !channel) {
        return;
    }

    window.Echo.private(channel).listen('BookingStatusUpdate', refreshBookings);
});

onUnmounted(() => {
    const channel = channelName();

    if (window.Echo && channel) {
        window.Echo.leave(channel);
    }
});
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
