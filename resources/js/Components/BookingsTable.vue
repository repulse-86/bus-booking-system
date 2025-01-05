<script setup>
import { ref } from 'vue';
import THead from '@/Components/THead.vue';
import TCellBooking from '@/Components/TCellBooking.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Paginator from '@/Components/Paginator.vue';
import { formatDate } from '@/helpers';

defineProps({
	bookings: {
		type: Object,
		required: true,
	},
	actionsVisible: {
		type: Boolean,
		default: false,
	},
	updateStatus: {
		type: Function,
		required: false,
	}
})

const headers = ref(['ID', 'Customer', 'Type', 'Date', 'From', 'To', 'Seat', 'Price']);

</script>

<template>
	<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
	    <THead :headers="headers" :actionsVisible="actionsVisible"/>
	    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
	        <tr v-for="(booking, index) in bookings.data" :key="booking.index" class="transition duration-300 ease-in-out hover:bg-neutral-100">
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="(booking.id).toString()"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="(booking.customer.name).toString()"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="(booking.bus.bus_type)"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="formatDate(booking.travel_date)"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="(booking.bus.departure_location).toString()"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="(booking.bus.destination_location).toString()"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="(booking.seat).toString()"/>
	            <TCellBooking :bookdId="booking.id" href="admin.bookings.show" :value="`P ${booking.bus.price_per_ticket}`" />
	            
	            <td v-if="actionsVisible" class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 break-words">
	                <div class="flex space-x-2">
                        <PrimaryButton 
                            v-if="updateStatus" 
                            @click="updateStatus(booking.id, 'approved')">Approve</PrimaryButton>
                        <SecondaryButton 
                            v-if="updateStatus" 
                            @click="updateStatus(booking.id, 'declined')">Decline</SecondaryButton>
                    </div>
	            </td>
	        </tr>
	    </tbody>
	</table>
	<Paginator :object="bookings"/>
</template>