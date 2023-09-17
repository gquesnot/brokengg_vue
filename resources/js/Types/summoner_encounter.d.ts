import {SummonerStatsInterface} from "@/types/summoner_stats";

export interface SummonerEncounterInterface {
    matches: LolMatchInterface[];
    summoner_stats: SummonerStatsInterface;
    encounter_stats: SummonerStatsInterface;
}
