import {FiltersInterface} from "@/types/filters";
import {SummonerStatsInterface} from "@/types/summoner_stats";

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    filters: Record<string, string>;
    champion_options: OptionInterface[];
    role_options: OptionInterface[];
    summoner: SummonerInterface;
    version: string;
    only: string[];
    route_params: Record<string, string>;



};
