<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {SummonerMatchInterface} from "@/types/summoner_match";
import MatchesRow from "@/Components/Summoner/MatchesRow.vue";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";


const props = defineProps<{
    summoner_match: SummonerMatchInterface
    summoner_encounter_count: SummonerEncounterCountInterface
}>();


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
