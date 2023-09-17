import {router} from "@inertiajs/vue3";


export const navigateTo = (route_name: string, params: Record<string, number | string>) => {
    router.visit(route(route_name, params), {
        preserveState: true,
    });
}


export const navigateToEncounter = (summoner_id: number, encounter_id: number) => {
    navigateTo('summoner.encounter', {
        summoner: summoner_id,
        encounter: encounter_id,
    })
}




export const navigateToMatch = (summoner_id: number, summoner_match_id: number) => {
    navigateTo('summoner.match', {
        summoner: summoner_id,
        summoner_match: summoner_match_id,
    })
}

export const navigateToSummoner = (summoner_id: number) => {
    navigateTo('summoner.matches', {
        summoner: summoner_id,
    })
}
