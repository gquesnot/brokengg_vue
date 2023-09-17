<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {ref} from "vue";
import EncounterPart from "@/Components/Summoner/EncounterPart.vue";
import {getSummoner} from "@/helpers/root_props_helpers";
import {SummonerEncounterInterface} from "@/types/summoner_encounter";


const props = defineProps<{
    encounter: SummonerInterface
    vs_: SummonerEncounterInterface
    with_: SummonerEncounterInterface
}>();


const tab = ref('with')
const summoner = getSummoner();


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
                <EncounterPart :encounter_data="with_" :summoner="summoner" :encounter="encounter"
                               :is_with="true"/>
            </VWindowItem>
            <VWindowItem value="vs">
                <EncounterPart :encounter_data="vs_" :summoner="summoner" :encounter="encounter"
                               :is_with="false"/>
            </VWindowItem>
        </VWindow>
    </div>

</template>

<style>

</style>
