import {PaginationLinkInterface} from "@/Types/pagination_link";

type SummonerMatchInterface = {
    id: number;
    won: boolean;
    kill_participation: number;
    champ_level: number;
    kda: number;
    assists: number;
    deaths: number;
    kills: number;
    minions_killed: number;
    largest_killing_spree: number;
    champion_id: number;
    summoner_id: number;
    match_id: number;
    double_kills: number;
    triple_kills: number;
    quadra_kills: number;
    penta_kills: number;
    total_damage_dealt_to_champions: number;
    gold_earned: number;
    total_damage_taken: number;
    match: null | LolMatchInterface;
    champion: null | ChampionInterface;
    summoner: null | SummonerInterface;
    items: null | ItemInterface[];
    other_participants: null | SummonerMatchInterface[];
}


export interface SummonerMatchesPaginated {
    data: SummonerMatchInterface[];
    links: PaginationLinkInterface[];
}

