import {PaginationLinkInterface} from "@/types/pagination_link";
import {LolMatchInterface} from "@/types/lol-match";
import {SummonerSpellInterface} from "@/types/summoner_spell";
import {SummonerMatchPerksInterface} from "@/types/summoner_match_perks";
import {SummonerMatchFrameInterface} from "@/types/summoner_match_frame";
import {SummonerInterface} from "@/types/summoner";

export interface SummonerMatchInterface {
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
    wards_placed: number;
    summoner_spell1_id: number;
    summoner_spell2_id: number;

    summoner_spell1: SummonerSpellInterface;
    summoner_spell2: SummonerSpellInterface;
    perks: SummonerMatchPerksInterface;
    match: LolMatchInterface;
    champion: ChampionInterface;
    summoner: SummonerInterface;
    items: ItemInterface[];
    frames: SummonerMatchFrameInterface[];


}


export interface SummonerMatchesPaginated {
    data: SummonerMatchInterface[];
    links: PaginationLinkInterface[];
}

