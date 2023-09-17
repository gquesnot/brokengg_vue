type ChampionInterface = {
    id: number;
    name: string;
    title: string;
    img_url: string;
    champion_id: string;
    stats: any[];
    matches?: SummonerMatchInterface[] | null;
}
