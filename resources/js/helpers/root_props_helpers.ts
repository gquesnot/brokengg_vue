import {usePage} from "@inertiajs/vue3";
import {useSummonerStore} from "@/store";


export const getParamsWithFilters = (filters: any, other_params: Record<string, string> = {}): Record<string, any> => {
    let new_filters = {
        champion_id: filters.champion_id,
        queue_id: filters.queue_id,
        start_date: filters.start_date,
        end_date: filters.end_date,
    }
    if (filters.should_filter_encounters) {
        new_filters = {...new_filters, ...{should_filter_encounters: filters.should_filter_encounters}}
    }
    return {
        ...other_params,
        filters: new_filters
    }
}


export const getRouteParams = (): Record<string, string> => usePage().props.route_params;

export const getVersion = (): string => {
    let summonerStore = useSummonerStore();
    return summonerStore.version;
}


