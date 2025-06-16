<script setup>
import SeatButton from '@/Components/SeatButton.vue';

const props = defineProps({
    seatRows: {
        type: Array,
        required: true,
    },
    takenSeats: {
        type: Array,
        required: true,
    },
    selectedSeats: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['select-seat']);

const handleSeatClick = (seatId) => {
    emit('select-seat', seatId);
};
</script>

<template>
    <div class="grid grid-cols-2 gap-12">
        <div class="space-y-4">
            <div
                v-for="(row, index) in seatRows"
                :key="'left-row-' + index"
                class="grid grid-cols-2 gap-2"
            >
                <div v-for="seat in row.left" :key="'seat-left-' + seat" class="text-center">
                    <SeatButton
                        :seat-id="seat"
                        :is-selected="selectedSeats.includes(seat)"
                        :is-taken="takenSeats.includes(seat)"
                        @click="handleSeatClick(seat)"
                    />
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div
                v-for="(row, index) in seatRows"
                :key="'right-row-' + index"
                class="grid grid-cols-2 gap-2"
            >
                <div v-for="seat in row.right" :key="'seat-right-' + seat" class="text-center">
                    <SeatButton
                        :seat-id="seat"
                        :is-selected="selectedSeats.includes(seat)"
                        :is-taken="takenSeats.includes(seat)"
                        @click="handleSeatClick(seat)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
