import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';

export const filterId = ref('');
export const filterCustomerName = ref('');

export const useDebouncedFilters = (routeName, delay = 500) => {
    const watchDebouncedCallback = debounce(() => {
        router.get(route(routeName), {
            filterId: filterId.value,
            filterCustomerName: filterCustomerName.value,
        }, { preserveState: true });
    }, delay);

    [filterId, filterCustomerName].forEach((field) => {
        watch(field, watchDebouncedCallback);
    });
};