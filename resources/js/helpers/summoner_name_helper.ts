import {router} from "@inertiajs/vue3";

export const withoutTagLine = (summoner_name: string) => {
    return summoner_name.split("#")[0];
}

