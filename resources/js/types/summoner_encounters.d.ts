import {PaginationLinkInterface} from "@/types/pagination_link";
import {SummonerInterface} from "@/types/summoner";

export interface EncounterInterface {
    summoner_id: number;
    name: string;
    encounter_count: number;
    summoner: SummonerInterface;

}

export interface SummonerEncountersPaginated {
    data: EncounterInterface[];
    links: PaginationLinkInterface[];
}


