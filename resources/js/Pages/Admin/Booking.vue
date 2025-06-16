<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingDetails from '@/Components/BookingDetails.vue';
import ReasonForDecline from '@/Components/ReasonForDecline.vue';

const props = defineProps({
    booking: {
        type: Object,
        required: true,
    },
    seats: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <AppLayout :title="booking.customer.name">
        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-200 leading-tight">
                {{ booking.customer.name }}'s ticket for {{ booking.bus.bus_type }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <BookingDetails :booking="booking" :seats="seats" />
                <ReasonForDecline :reason="booking.reason_declined" />
            </div>
        </div>
    </AppLayout>
</template>
