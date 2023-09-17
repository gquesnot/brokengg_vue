import {getVersion} from "@/helpers/root_props_helpers";

export const urlChampionHelper = (img_url: string | undefined): string => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/champion/${img_url}`;
export const urlItemHelper = (img_url: string): string => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/item/${img_url}`;

export const urlProfilIconHelper = (img_id: number): string => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/profileicon/${img_id.toString()}.png`;
