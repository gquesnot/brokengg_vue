import {SummonerMatchInterface} from "@/types/summoner-match";

type LolMatchInterface = {
    id: number;
    updated: boolean;
    match_id: string;
    mode_id: number | null;
    map_id: number | null;
    queue_id: number | null;
    match_creation: string /* Date */ | null;
    match_end: string /* Date */ | null;
    match_duration: string /* Date */ | null;
    is_trashed: boolean;
    created_at: string /* Date */ | null;
    updated_at: string /* Date */ | null;
    participants?: SummonerMatchInterface[] | null;
    map?: MapInterface | null;
    queue?: QueueInterface | null;
    mode ?: ModeInterface | null;

}
