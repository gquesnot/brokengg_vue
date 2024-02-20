import {router} from "@inertiajs/vue3";
import {useFiltersStore} from "@/store";


export const navigateTo = (route_name: string, params: Record<string, number | string>) => {
    const filtersStore = useFiltersStore();

    router.visit(route(route_name, {...params, ...filtersStore.toObj()}), {
        preserveState: true,
    });
}


export const navigateToEncounter = (summoner_id: number, encounter_id: number) => {
    navigateTo('summoner.encounter', {
        summoner_id: summoner_id,
        encounter_id: encounter_id,
    })
}


export const navigateToMatch = (summoner_id: number, summoner_match_id: number) => {
    navigateTo('summoner.match', {
        summoner_id: summoner_id,
        summoner_match_id: summoner_match_id,
    })
}

export const navigateToSummoner = (summoner_id: number) => {
    const filtersStore = useFiltersStore();
    router.visit(route('summoner.matches', {
        summoner_id: summoner_id,
        ...filtersStore.toObj()
    }), {});
}
