<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    object: {
        type: Object,
        required: true,
    }
});

// Extract necessary object properties
const currentPage = props.object.current_page;
const totalPages = props.object.last_page;
const totalItems = props.object.total;
const perPage = props.object.per_page;

console.log(totalItems);

// Safely calculate start and end item numbers
const startItem = totalItems > 0 ? (currentPage - 1) * perPage + 1 : 0;
const endItem = totalItems > 0 ? Math.min(currentPage * perPage, totalItems) : 0;

const showPrevious = currentPage > 1;
const showNext = currentPage < totalPages;

const pageNumbers = [];
// Generate page numbers for pagination, limiting to a range of pages
for (let i = 1; i <= totalPages; i++) {
    pageNumbers.push(i);
}
</script>

<template>
    <!-- Right side: Pagination controls -->
    <div class="flex items-center justify-between border-t border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6">
        <!-- Mobile Pagination Controls -->
        <div class="flex flex-1 justify-between sm:hidden">
            <button
                @click="router.get(props.object.prev_page_url)"
                :disabled="!showPrevious"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Previous
            </button>
            <button
                @click="router.get(props.object.next_page_url)"
                :disabled="!showNext"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Next
            </button>
        </div>

        <!-- Desktop Pagination Controls -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <!-- Display Pagination Information -->
            <div>
                <p class="text-sm text-gray-800 dark:text-gray-300">
                    Showing
                    <span class="font-medium">{{ startItem }}</span>
                    to
                    <span class="font-medium">{{ endItem }}</span>
                    of
                    <span class="font-medium">{{ totalItems }}</span>
                    results
                </p>
            </div>

            <!-- Page Number Buttons -->
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Previous Page Button -->
                    <button
                        @click="router.get(props.object.prev_page_url, {}, { preserveScroll: true })"
                        :disabled="!showPrevious"
                        class="relative inline-flex items-center rounded-md dark:bg-gray-200 border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Previous
                    </button>

                    <button
                        v-for="page in pageNumbers"
                        :key="page"
                        :class="{'bg-indigo-600 text-white': currentPage === page, 'text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 hover:bg-gray-50': currentPage !== page}"
                        @click="router.get(props.object.path + `?page=${page}`, {}, { preserveScroll: true })"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                    >
                        {{ page }}
                    </button>

                    <button
                        @click="router.get(props.object.next_page_url, {}, { preserveScroll: true })"
                        :disabled="!showNext"
                        class="relative ml-3 inline-flex items-center rounded-md dark:bg-gray-200 border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>
