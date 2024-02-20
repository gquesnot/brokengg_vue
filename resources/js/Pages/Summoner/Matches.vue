<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import Pagination from "@/Components/Pagination.vue";
import SummonerStats from "@/Components/Summoner/SummonerStats.vue";
import MatchesRow from "@/Components/Summoner/MatchesRow.vue";
import {SummonerMatchesPaginated} from "@/types/summoner_match";
import {SummonerStatsInterface} from "@/types/summoner_stats";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";
import {useFiltersStore, useSummonerStore} from "@/store";
import {SummonerInterface} from "@/types/summoner";
import {BeforeFiltersInterface} from "@/types/filters";
import {OptionInterface} from "@/types/option";


const props = defineProps<{
    matches: SummonerMatchesPaginated
    summoner_stats: SummonerStatsInterface | null
    summoner_encounter_count: SummonerEncounterCountInterface
    summoner: SummonerInterface,
    filters: BeforeFiltersInterface,
    version: string,
    champion_options: OptionInterface[],
    queue_options: OptionInterface[],
    only: string[],
}>();

const summonerStore = useSummonerStore();
const filtersStore = useFiltersStore();
summonerStore.setStore(props.summoner, props.version, props.champion_options, props.queue_options, props.only);
filtersStore.setStore(props.filters);


</script>

<template>

    <div class="w-7/12 mx-auto my-6">

        <SummonerHeader
            tab="Matches"
        />

        <SummonerStats v-if="summoner_stats" :summoner_stats="summoner_stats" :summoner="summonerStore.summoner"
                       justify="start"/>
        <div class="flex flex-col" v-for="match in matches.data" :key="match.id">
            <MatchesRow
                :key="match.id"
                :summoner_match="match"
                :summoner_encounter_count="summoner_encounter_count"
            />
        </div>
        <Pagination :links="matches.links"
        />
    </div>


</template>

<style>

</style>
