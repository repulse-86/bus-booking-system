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
                        @click="router.get(props.object.prev_page_url)"
                        :disabled="!showPrevious"
                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Previous</span>
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Page Number Buttons -->
                    <button
                        v-for="page in pageNumbers"
                        :key="page"
                        :class="{'bg-indigo-600 text-white': currentPage === page, 'text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 hover:bg-gray-50': currentPage !== page}"
                        @click="router.get(props.object.path + `?page=${page}`)"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                    >
                        {{ page }}
                    </button>

                    <!-- Next Page Button -->
                    <button
                        @click="router.get(props.object.next_page_url)"
                        :disabled="!showNext"
                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Next</span>
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>
