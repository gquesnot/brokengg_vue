<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner-match";
import {getSummoner} from "@/helpers/root_props_helpers";
import MatchTable from "@/Components/Summoner/MatchTable.vue";
import {getDurationMinutes} from "@/helpers/duration_helpers";
import {computed} from "vue";


const props = defineProps<{
  summoner_match: SummonerMatchInterface|null,
}>();



const summoner = getSummoner();



</script>

<template>
    <template v-if="summoner_match !== null && summoner_match.match !== null">
        <div class="my-1 bg-gray-700 p-4 rounded text-gray-200  w-full">
            <MatchTable :duration_minutes="getDurationMinutes(summoner_match.match.match_duration)"  :won="summoner_match.won" :participants="summoner_match.match.participants"/>
            <MatchTable :duration_minutes="getDurationMinutes(summoner_match.match.match_duration)"  class="mt-2" :won="!summoner_match.won" :participants="summoner_match.match.participants "/>
        </div>
    </template>
    <template v-else>
        <div class="my-1 bg-gray-700 p-4 rounded text-gray-200  w-full h-64">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-gray-900"></div>
            </div>
        </div>
    </template>

</template>

