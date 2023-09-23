import {PerkInterface} from "@/types/perk";

export interface SummonerMatchPerksInterface {
    id: number;
    summoner_match_id: number;
    offense_id: number;
    defense_id: number;
    flex_id: number;
    primary_style_id: number;
    primary_selection_id: number;
    primary_selection1_id: number;
    primary_selection2_id: number;
    primary_selection3_id: number;
    sub_style_id: number;
    sub_selection1_id: number;
    sub_selection2_id: number;

    offense: PerkInterface;
    defense: PerkInterface;
    flex: PerkInterface;
    primary_style: PerkInterface;
    primary_selection: PerkInterface;
    primary_selection1: PerkInterface;
    primary_selection2: PerkInterface;
    primary_selection3: PerkInterface;
    sub_style: PerkInterface;
    sub_selection1: PerkInterface;
    sub_selection2: PerkInterface;
}
