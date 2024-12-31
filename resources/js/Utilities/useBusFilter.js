import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';

export const filterBus = ref('');
export const filterTravelDate = ref('');
export const filterDestinationLocation = ref('');

export const useDebouncedFilters = (routeName, delay = 500) => {
    const watchDebouncedCallback = debounce(() => {
        router.get(route(routeName), {
            filterBus: filterBus.value,
            filterTravelDate: filterTravelDate.value,
            filterDestinationLocation: filterDestinationLocation.value,
        }, { preserveState: true });
    }, delay);

    [filterBus, filterTravelDate, filterDestinationLocation].forEach((field) => {
        watch(field, watchDebouncedCallback);
    });
};
