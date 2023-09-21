<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";
import {getSummoner} from "@/helpers/root_props_helpers";
import MatchTable from "@/Components/Summoner/MatchTable.vue";
import {getDurationMinutes} from "@/helpers/duration_helpers";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";
import {ref, watch} from "vue";
import LinearStat from "@/Components/LinearStat.vue";
import CircularStat from "@/Components/CircularStat.vue";
import MatchDetailBuild from "@/Components/Summoner/MatchDetailBuild.vue";
import axios from "axios";

const props = defineProps<{
  summoner_match: SummonerMatchInterface,
  summoner_encounter_count: SummonerEncounterCountInterface
}>();


const tab = ref('Overview')

const summoner = getSummoner();
const match_detail  = ref<any | null>(null);

watch(tab, function (value){
    if (value === 'build' && match_detail.value === null){
        axios.get(route('summoner.match.detail', {
          summoner_match: props.summoner_match.id,
          summoner: getSummoner().id
        }))
            .then(response => {
                match_detail.value = response.data.match_detail
            })
            .catch(error => console.log('error', error))
    }
})


</script>

<template>
  <div class="my-1 bg-gray-1 p-4 rounded text-gray-200  w-full">
    <v-tabs
        v-model="tab"
        bg-color="dark"
    >
      <v-tab value="overview">Overview</v-tab>
      <v-tab value="team_analysis">Team analysis</v-tab>
        <v-tab value="build" >Build</v-tab>
    </v-tabs>
    <v-window v-model="tab">
      <v-window-item value="overview">
        <MatchTable :summoner_encounter_count="summoner_encounter_count"
                    :duration_minutes="getDurationMinutes(summoner_match.match.match_duration)"
                    :won="summoner_match.won" :participants="summoner_match.match.participants"/>
        <div class="flex justify-center flex-col my-4">
          <LinearStat class="mx-auto w-2/3" key_stat="kills" :participants="summoner_match.match.participants"
                      description="Team kill" :has_won="summoner_match.won"/>
          <LinearStat class="mx-auto w-2/3 mt-2" key_stat="gold_earned" :participants="summoner_match.match.participants"
                      description="Team Gold" :has_won="summoner_match.won"/>
        </div>
        <MatchTable :summoner_encounter_count="summoner_encounter_count"
                    :duration_minutes="getDurationMinutes(summoner_match.match.match_duration)"
                    :won="!summoner_match.won" :participants="summoner_match.match.participants "/>
      </v-window-item>
      <v-window-item value="team_analysis">
        <div class="flex justify-center">
          <div class="flex items-center justify-center">
            <div class="w-4 h-4 rounded-full bg-[#64B5F6] mr-3"></div>
            <div>
              Winning team
            </div>
          </div>
          <div class="ml-10 flex items-center justify-center">
            <div class="w-4 h-4 rounded-full bg-[#E57373] mr-3"></div>
            <div>
              Losing team
            </div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <CircularStat description="Champion Kill" :split_number="false" :participants="summoner_match.match.participants"  key_stat="kills"/>
          <CircularStat description="Gold Earned" :split_number="true" :participants="summoner_match.match.participants"  key_stat="gold_earned"/>
          <CircularStat description="Damage Dealt to Champions" :split_number="true" :participants="summoner_match.match.participants"  key_stat="total_damage_dealt_to_champions"/>
          <CircularStat description="Wards Placed" :split_number="false" :participants="summoner_match.match.participants"  key_stat="wards_placed"/>
          <CircularStat description="Damage Taken" :split_number="true" :participants="summoner_match.match.participants"  key_stat="total_damage_taken"/>
          <CircularStat description="CS" :split_number="false" :participants="summoner_match.match.participants"  key_stat="minions_killed"/>
        </div>
      </v-window-item>
        <v-window-item value="build">
            <MatchDetailBuild v-if="match_detail !== null" :summoner_match="summoner_match"  :match_detail="match_detail"/>
            <template v-else>
                <div class="w-full h-32 flex justify-center items-center bg-gray-1">
                    <v-progress-circular
                            :size="70"
                            :width="7"
                            color="black"
                            indeterminate
                    ></v-progress-circular>
                </div>
            </template>
        </v-window-item>
    </v-window>

  </div>
</template>

