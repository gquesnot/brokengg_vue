<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner-match";
import {getSummoner} from "@/helpers/root_props_helpers";
import MatchTable from "@/Components/Summoner/MatchTable.vue";
import {getDurationMinutes} from "@/helpers/duration_helpers";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";


const props = defineProps<{
    summoner_match: SummonerMatchInterface,
    summoner_encounter_count: SummonerEncounterCountInterface
}>();


const summoner = getSummoner();


</script>

<template>
    <div class="my-1 bg-gray-700 p-4 rounded text-gray-200  w-full">
        <MatchTable :summoner_encounter_count="summoner_encounter_count"
                    :duration_minutes="getDurationMinutes(summoner_match.match.match_duration)"
                    :won="summoner_match.won" :participants="summoner_match.match.participants"/>
        <MatchTable :summoner_encounter_count="summoner_encounter_count"
                    :duration_minutes="getDurationMinutes(summoner_match.match.match_duration)" class="mt-2"
                    :won="!summoner_match.won" :participants="summoner_match.match.participants "/>
    </div>
</template>

