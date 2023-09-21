import {PerkInterface} from "@/types/perk";

export interface SummonerMatchPerksInterface {
    id: number;
    summoner_match_id: number;
    offense_id: number;
    defense_id: number;
    flex_id: number;
    primary_style_id: number;
    primary_style1_id: number;
    primary_style2_id: number;
    primary_style3_id: number;
    sub_style_id: number;
    sub_style1_id: number;
    sub_style2_id: number;

    offense: PerkInterface;
    defense: PerkInterface;
    flex: PerkInterface;
    primary_style: PerkInterface;
    primary_style1: PerkInterface;
    primary_style2: PerkInterface;
    primary_style3: PerkInterface;
    sub_style: PerkInterface;
    sub_style1: PerkInterface;
    sub_style2: PerkInterface;
}
