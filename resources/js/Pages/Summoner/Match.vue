<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {SummonerMatchInterface} from "@/types/summoner_match";
import MatchesRow from "@/Components/Summoner/MatchesRow.vue";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";
import {SummonerInterface} from "@/types/summoner";
import {BeforeFiltersInterface} from "@/types/filters";
import {useFiltersStore, useSummonerStore} from "@/store";
import {OptionInterface} from "@/types/option";


const props = defineProps<{
    summoner_match: SummonerMatchInterface
    summoner_encounter_count: SummonerEncounterCountInterface,
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
        <MatchesRow :auto_open="true" :summoner_match="summoner_match"
                    :summoner_encounter_count="summoner_encounter_count"/>

    </div>


</template>

<style>

</style>
