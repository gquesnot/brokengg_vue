import {SummonerStatsInterface} from "@/Types/summoner_stats";

export interface SummonerEncounterInterface {
    matches: LolMatchInterface[];
    summoner_stats: SummonerStatsInterface;
    encounter_stats: SummonerStatsInterface;
}
