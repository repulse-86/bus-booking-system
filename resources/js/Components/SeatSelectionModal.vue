<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import SeatLayout from '@/Components/SeatLayout.vue';

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
    emit('select-seat', seat);
};

const handleCloseModal = () => {
    emit('toggle-modal');
};
</script>

<template>
    <Modal :show="isOpenSeatModal" @close="handleCloseModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 h-4/5 overflow-hidden flex flex-col space-y-8">
            <h3 class="text-xl dark:text-gray-300 font-bold mb-2">Bus Seat Layout</h3>
            <div class="flex-1 overflow-y-auto">
                <SeatLayout
                    :seat-rows="seatRows"
                    :taken-seats="takenSeats"
                    :selected-seats="selectedSeats"
                    @select-seat="handleSeatClick"
                />
            </div>
            <SecondaryButton
                type="button"
                class="p-4 w-full justify-center"
                @click="handleCloseModal"
            >
                Close
            </SecondaryButton>
        </div>
    </Modal>
</template>
