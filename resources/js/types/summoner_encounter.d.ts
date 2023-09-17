import {SummonerStatsInterface} from "@/types/summoner_stats";
import {LolMatchInterface} from "@/types/lol-match";

export interface SummonerEncounterInterface {
    matches: LolMatchInterface[];
    summoner_stats: SummonerStatsInterface;
    encounter_stats: SummonerStatsInterface;
}
