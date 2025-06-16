<script setup>
import { computed } from 'vue';

const props = defineProps({
    seatId: {
        type: [String, Number],
        required: true,
    },
    isSelected: {
        type: Boolean,
        default: false,
    },
    isTaken: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['click']);

const buttonClass = computed(() => {
    if (props.isTaken) {
        return 'bg-red-300 dark:bg-red-700 text-white opacity-75 cursor-not-allowed';
    }
    if (props.isSelected) {
        return 'bg-gray-800 dark:bg-gray-300 text-white dark:text-gray-800';
    }
    return 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white';
});

const handleClick = () => {
    if (!props.isTaken) {
        emit('click', props.seatId);
    }
};
</script>

<template>
    <button
        type="button"
        @click="handleClick"
        :disabled="isTaken"
        :class="[buttonClass, 'w-full py-2 px-4 rounded-lg font-medium']"
    >
        {{ seatId }}
    </button>
</template>
