import {PaginationLinkInterface} from "@/types/pagination_link";

export interface ChampionStatsInterface {
    champion: ChampionInterface;
    id:number;
    champion_id: number;
    total: number;
    wins: number;
    avg_kills: number;
    avg_deaths: number;
    avg_assists: number;
    max_kills: number;
    max_deaths: number;
    max_assists: number;
    avg_cs: number;
    avg_damage_dealt_to_champions: number;
    avg_damage_taken: number;
    avg_gold: number;
    total_double_kills: number;
    total_triple_kills: number;
    total_quadra_kills: number;
    total_penta_kills: number;
}

export interface CustomChampionPaginated{
    data: ChampionStatsInterface[];
    links: PaginationLinkInterface[];
}
