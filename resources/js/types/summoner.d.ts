import {ProPlayerInterface} from "@/types/pro_player";

type SummonerInterface = {
    id: number;
    name: string;
    profile_icon_id: number;
    summoner_level: number;
    pro_player: ProPlayerInterface | null;
}
