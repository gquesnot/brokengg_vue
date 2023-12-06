import {ProPlayerInterface} from "@/types/pro_player";
import {SummonerLeagueInterface} from "@/types/summoner_league";

type SummonerInterface = {
    id: number;
    name: string;
    profile_icon_id: number;
    summoner_level: number;
    pro_player: ProPlayerInterface | null;
    solo_q: SummonerLeagueInterface | null;
}
