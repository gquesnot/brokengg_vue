import {PaginationLinkInterface} from "@/types/pagination_link";

export interface EncounterInterface {
    summoner_id: number;
    name: string;
    encounter_count: number;

}

export interface SummonerEncountersPaginated {
    data: EncounterInterface[];
    links: PaginationLinkInterface[];
}


