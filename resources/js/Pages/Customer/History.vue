<script setup>
import { router, WhenVisible } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import HistoryContainer from '@/Components/HistoryContainer.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    bookings: Array,
    currentPage: Number,
    lastPage: Number,
});

const loadMore = () => {
    router.reload({
        data: {
            page: props.currentPage + 1,
        },
        only: ['bookings', 'currentPage'],
    });
};

</script>

<template>
    <AppLayout title="My Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Bookings
            </h2>
        </template>

        <div class="py-12">
            <div class="overflow-x-auto max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <HistoryContainer v-for="(booking, index) in bookings" :key="index" :booking="booking"/>
                
            </div>
            <div v-if="currentPage < lastPage" class="text-center p-6">
                <PrimaryButton @click="loadMore">Load more</PrimaryButton>
            </div>
        </div>
    </AppLayout>
</template>
