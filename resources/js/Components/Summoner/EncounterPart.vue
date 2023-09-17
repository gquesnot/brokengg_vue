<script setup lang="ts">


import moment from 'moment';
import {router} from "@inertiajs/vue3";
import SummonerStats from "@/Components/Summoner/SummonerStats.vue";
import EncounterRow from "@/Components/Summoner/EncounterRow.vue";
import {navigateToMatch} from "@/helpers/router_helpers";
import {SummonerEncounterInterface} from "@/types/summoner_encounter";


const props = defineProps<{
  encounter_data: SummonerEncounterInterface,
  summoner: SummonerInterface,
  is_with: boolean,
  encounter: SummonerInterface,
}>();



const has_won = (match: any): boolean => {
  return match.participants.filter((participant: any) => {
    return participant.summoner_id === props.summoner.id
  })[0].won
}

const my_summoner_match_id = (match: any): number => {
  return match.participants.filter((participant: any) => {
    return participant.summoner_id === props.summoner.id
  })[0].id
}


</script>

<template>
  <template v-if="encounter_data.matches.length > 0">
    <div class="grid grid-cols-2">
      <div>
        <SummonerStats :with_summoner_name="true"  :summoner_stats="encounter_data.summoner_stats"
                       :summoner="summoner" color="blue"/>
      </div>
      <div>
        <SummonerStats :with_summoner_name="true"  :is_reverse="true"
                       :summoner_stats="encounter_data.encounter_stats" :summoner="encounter"
                       :color="is_with ? 'blue':'red'"/>
      </div>
    </div>
    <div>
      <div v-for="match in encounter_data.matches" :key="match.id" class="grid grid-cols-3 my-4">
        <template v-for="participant in match.participants" :key="participant.summoner_id+'_'+participant.match_id">
          <EncounterRow :is_reverse="false" :summoner_match="participant"  class="mr-4"
                        v-if="participant.summoner_id === summoner.id"/>
        </template>
        <div @click="navigateToMatch(summoner.id, my_summoner_match_id(match))"
             :class="`${!(match.participants) || has_won(match) ?'bg-blue-500':'bg-red-500'} flex flex-col items-center justify-center cursor-pointer`">
          <div>{{ match?.queue?.description }}</div>
          <div>{{ moment(match?.match_end).fromNow() }}</div>
          <div>{{ !(match.participants) || has_won(match) ? 'Victory' : 'Defeat' }}</div>
          <div>{{ match?.match_duration }}</div>
        </div>
        <template v-for="participant in match.participants" :key="participant.summoner_id+'_'+participant.match_id">
          <EncounterRow :is_reverse="true" :summoner_match="participant"  class="ml-4"
                        v-if="participant.summoner_id === encounter.id"/>
        </template>
      </div>
    </div>
  </template>
  <template v-else>
    <div class="text-center text-2xl font-bold mt-4">
      No Matches Found
    </div>
  </template>

</template>

<style>

</style>
