<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {ref} from "vue";
import EncounterPart from "@/Components/Summoner/EncounterPart.vue";
import {SummonerEncounterInterface} from "@/types/summoner_encounter";
import {SummonerInterface} from "@/types/summoner";
import {useFiltersStore, useSummonerStore} from "@/store";
import {BeforeFiltersInterface} from "@/types/filters";
import {OptionInterface} from "@/types/option";


const props = defineProps<{
    encounter: SummonerInterface
    vs_: SummonerEncounterInterface
    with_: SummonerEncounterInterface,
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

const tab = ref('with')


</script>

<template>
    <div class="w-7/12 mx-auto my-6">

        <SummonerHeader
            tab="Encounters"
        />
        <VTabs
            v-model="tab"
            align-tabs="center"
            :color="tab === 'with' ? 'blue' : 'red'"
        >
            <VTab value="with" class="bg-blue-500">WITH</VTab>
            <VTab value="vs" class="bg-red-500">VS</VTab>
        </VTabs>

        <VWindow
            v-model="tab"

        >
            <VWindowItem value="with">
                <EncounterPart :encounter_data="with_" :summoner="summonerStore.summoner" :encounter="encounter"
                               :is_with="true"/>
            </VWindowItem>
            <VWindowItem value="vs">
                <EncounterPart :encounter_data="vs_" :summoner="summonerStore.summoner" :encounter="encounter"
                               :is_with="false"/>
            </VWindowItem>
        </VWindow>
    </div>

</template>

<style>

</style>
