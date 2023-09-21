import {PerkInterface} from "@/types/perk";
import {ItemEventInterface} from "@/types/item_event";
import {LevelUpSkillEventInterface} from "@/types/level_up_skill_event";

export interface SummonerMatchFrameInterface {
    id: number;
    current_timestamp: number;
    match_id:number;
    summoner_match_id:number;
    item_events: ItemEventInterface[];
    level_up_skill_events: LevelUpSkillEventInterface[];
}
