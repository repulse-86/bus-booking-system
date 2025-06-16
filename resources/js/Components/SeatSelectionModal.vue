<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    isOpenSeatModal: {
        type: Boolean,
        required: true,
    },
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
    }
});

const emit = defineEmits(['toggle-modal', 'select-seat']);

const handleSeatClick = (seat) => {
    if (!props.takenSeats.includes(seat)) {
        emit('select-seat', seat);
    }
};

const handleCloseModal = () => {
    emit('toggle-modal');
};
</script>

<template>
    <div v-if="isOpenSeatModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="handleCloseModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 h-4/5 overflow-hidden flex flex-col space-y-8">
            <h3 class="text-xl dark:text-gray-300 font-bold mb-2">Bus Seat Layout</h3>
            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-2 gap-12">
                    <div class="space-y-4">
                        <div
                            v-for="(row, index) in seatRows"
                            :key="'left-row-' + index"
                            class="grid grid-cols-2 gap-2"
                        >
                            <div v-for="seat in row.left" :key="'seat-left-' + seat" class="text-center">
                                <button
                                    type="button"
                                    @click="handleSeatClick(seat)"
                                    :disabled="takenSeats.includes(seat)"
                                    :class="{
                                        'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800': selectedSeats.includes(seat),
                                        'bg-gray-200 dark:bg-gray-700 text-black dark:text-white': !selectedSeats.includes(seat) && !takenSeats.includes(seat),
                                        'bg-red-300 dark:bg-red-700 text-white opacity-75 cursor-not-allowed': takenSeats.includes(seat)
                                    }"
                                    class="w-full py-2 px-4 rounded-lg font-medium"
                                >
                                    {{ seat }}
                                </button>
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
                                <button
                                    type="button"
                                    @click="handleSeatClick(seat)"
                                    :disabled="takenSeats.includes(seat)"
                                    :class="{
                                        'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800': selectedSeats.includes(seat),
                                        'bg-gray-200 dark:bg-gray-700 text-black dark:text-white': !selectedSeats.includes(seat) && !takenSeats.includes(seat),
                                        'bg-red-500 dark:bg-red-700 text-white opacity-75 cursor-not-allowed': takenSeats.includes(seat)
                                    }"
                                    class="w-full py-2 px-4 rounded-lg font-medium"
                                >
                                    {{ seat }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <SecondaryButton
                type="button"
                class="p-4 w-full justify-center"
                @click="handleCloseModal"
            >
                Close
            </SecondaryButton>
        </div>
    </div>
</template>
