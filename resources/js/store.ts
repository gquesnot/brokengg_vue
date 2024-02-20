import {defineStore} from "pinia";
import {SummonerInterface} from "@/types/summoner";
import {OptionInterface} from "@/types/option";
import {BeforeFiltersInterface, FiltersInterface} from "./types/filters";


export const useFiltersStore = defineStore({
    id: "filters",
    state: () => {
        return <FiltersInterface>{
            champion_id: undefined,
            queue_id: undefined,
            start_date: undefined,
            end_date: undefined,
            should_filter_encounters: false,
        }
    },
    actions: {
        setStore(filters: BeforeFiltersInterface) {
            this.champion_id = filters.champion_id ? parseInt(filters.champion_id) : undefined;
            this.queue_id = filters.queue_id ? parseInt(filters.queue_id) : undefined;
            this.start_date = filters.start_date;
            this.end_date = filters.end_date;
            //@ts-ignore
            this.should_filter_encounters = filters.should_filter_encounters == "1";
        },
        toObj() {
            let obj = {
                champion_id: this.champion_id,
                queue_id: this.queue_id,
                start_date: this.start_date,
                end_date: this.end_date,
            }
            if (this.should_filter_encounters) {
                obj = {...obj, ...{should_filter_encounters: this.should_filter_encounters}}
            }
            return {
                filters: obj
            }
        }
    }

})

interface SummonerStoreInterface {
    champion_options: OptionInterface[];
    queue_options: OptionInterface[];
    summoner: SummonerInterface;
    version: string;
    only: string[];
}

export const useSummonerStore = defineStore({
    id: "summoner",
    state: () => (<SummonerStoreInterface>{
        champion_options: [],
        queue_options: [],
        summoner: <SummonerInterface>{
            id: 0,
            name: "",
            profile_icon_id: 0,
            summoner_level: 0,
        },
        version: "10.0.0",
        only: [],
    }),
    actions: {
        setStore(summoner: SummonerInterface, version: string, champion_options: OptionInterface[], queue_options: OptionInterface[], only: string[] = []) {
            this.summoner = summoner;
            this.version = version;
            this.champion_options = champion_options;
            this.queue_options = queue_options;
            this.only = only;
        }
    }
})
