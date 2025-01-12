<script setup>
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Paginator from '@/Components/Paginator.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import THead from '@/Components/THead.vue';
import TCellBooking from '@/Components/TCellBooking.vue';
import BookingStatusBadge from '@/Components/BookingStatusBadge.vue';
import { formatDate } from '@/helpers';

defineProps({
    bookings: {
        type: Object,
        required: true,
    }
});

const filterId = ref('');
const filterStatus = ref('');

const options = [
    { label: 'Pending', value: 'pending' },
    { label: 'Approved', value: 'approved' },
    { label: 'Declined', value: 'declined' },
];

const headers = ref(['#', 'Type', 'Date', 'From', 'To', 'Seat', 'Price', 'Status']);

const watchDebounced = (field, value) => {
    router.get(route('customer.bookings.index'), {
        filterId: filterId.value,
        filterStatus: filterStatus.value,
    }, { preserveState: true });
};

watch(filterId, debounce((newValue) => watchDebounced('filterId', newValue), 500));
watch(filterStatus, debounce((newValue) => watchDebounced('filterStatus', newValue), 500));
</script>

<template>
    <AppLayout title="My Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Bookings
            </h2>
        </template>

        <div class="py-12">
            <!-- Filters Section -->
            <div class="max-w-7xl mx-auto mb-6">
                <div class="flex flex-col sm:flex-row justify-start gap-4 mb-8">

                    <TextInput v-model="filterId" placeholder="Search bookings"/>
                    <SelectInput v-model="filterStatus" :options="options" selected="Status"/>
                    
                </div>
            </div>
            <div class="overflow-x-auto max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <THead :headers="headers" :actionsVisible="false"/>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(booking, index) in bookings.data" :key="booking.index" class="transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-600">
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="(booking.id).toString()"/>
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="(booking.bus.bus_type)"/>
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="formatDate(booking.travel_date)"/>
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="(booking.bus.departure_location).toString()"/>
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="(booking.bus.destination_location).toString()"/>
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="(booking.seat).toString()"/>
                            <TCellBooking :bookdId="booking.id" href="customer.bookings.show" :value="`P ${booking.bus.price_per_ticket}`" />
                            
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                <Link :href="route('customer.bookings.show', booking.id)">
                                    <BookingStatusBadge :status="booking.status"/>
                                </Link>
                            </td>
                        </tr>
                    </tbody>

                </table>
                <Paginator :object="bookings"/>
            </div>
        </div>
    </AppLayout>
</template>
