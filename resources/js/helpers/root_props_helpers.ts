import {usePage} from "@inertiajs/vue3";
import {FiltersInterface} from "@/types/filters";
import {SummonerInterface} from "@/types/summoner";


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


export const getFilters = (): FiltersInterface => {
    const base_filters = usePage().props.filters;
    return {
        champion_id: base_filters.champion_id ? parseInt(base_filters.champion_id) : undefined,
        queue_id: base_filters.queue_id ? parseInt(base_filters.queue_id) : undefined,
        start_date: base_filters.start_date,
        end_date: base_filters.end_date,
        should_filter_encounters: base_filters.should_filter_encounters == "1"
    };
}
export const getSummoner = (): SummonerInterface => usePage().props.summoner;

export const getVersion = (): string => usePage().props.version;


export const getOnly = (): string[] => usePage().props.only;


export const getRouteParams = (): Record<string, string> => usePage().props.route_params;


export const getChampionOptions = (): OptionInterface[] => usePage().props.champion_options;

// @ts-ignore
export const getQueueOptions = (): OptionInterface[] => usePage().props.queue_options;


