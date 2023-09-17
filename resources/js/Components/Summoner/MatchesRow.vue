<script setup lang="ts">


import moment from 'moment';
import {router} from "@inertiajs/vue3";
import {urlChampionHelper, urlItemHelper} from "@/helpers/url_helpers";
import MatchesRowPart from "@/Components/Summoner/MatchesRowPart.vue";
import {SummonerMatchInterface} from "@/types/summoner-match";
import {getSummoner} from "@/helpers/root_props_helpers";
import {ref} from "vue";
import axios from "axios";
import MatchDetail from "@/Components/Summoner/MatchDetail.vue";
import {getDurationMinutes} from "@/helpers/duration_helpers";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {navigateToMatch} from "@/helpers/router_helpers";


const props = defineProps<{
  summoner_match: SummonerMatchInterface
  summoner_encounter_count: SummonerEncounterCountInterface
}>();

let is_open = ref(false);
const loaded_summoner_match = ref<SummonerMatchInterface|null>(null)


const summoner = getSummoner();


const toggleIsOpen = () => {
  is_open.value = !is_open.value
  if (loaded_summoner_match.value ===null && is_open.value) {
    axios.get(route('get-summoner-match-loaded', {
      summoner_match: props.summoner_match.id
    })).then((response) =>{
        loaded_summoner_match.value = response.data.summoner_match
      })
  }
}






</script>

<template>
  <div
      :class="`${summoner_match.won ? 'bg-blue-950' : 'bg-red-900'}  my-1.5 flex rounded opacity-95 text-gray-200`">
    <div :class="`${summoner_match.won ? 'bg-blue-600' : 'bg-red-700'} w-3 rounded-l`"></div>
    <div class="w-28 flex flex-col justify-center space-y-1 my-2 pl-4">
      <div>{{ summoner_match.match?.queue?.description.replace('games', '') }}</div>
      <div>{{ moment(summoner_match.match?.match_end).fromNow() }}</div>
      <div>{{ summoner_match.won ? 'Victory' : 'Defeat' }}</div>
      <div>{{ summoner_match.match?.match_duration }}</div>
    </div>
    <div class="ml-4 w-64 flex flex-col justify-center my-2">
      <div class="w-full flex">
        <div class="w-16 h-16">
          <VImg
              :src="urlChampionHelper(summoner_match.champion?.img_url)"
              class="w-16 h-16"/>
        </div>
        <div class="ml-4 text-xl flex justify-center items-center flex-col">
          <div class="flex">
            <div class="text-gray-200 font-bold">{{ summoner_match.kills }}</div>
            <div class="text-gray-500 mx-1">/</div>
            <div class="text-red font-bold">{{ summoner_match.deaths }}</div>
            <div class="text-gray-500 mx-1">/</div>
            <div class="text-gray-200 font-bold">{{ summoner_match.assists }}</div>
          </div>
          <div>
            {{ summoner_match.kda?.toFixed(2) }}:1 KDA
          </div>
        </div>
      </div>
      <div class="flex mt-4 space-x-2 my-2">
        <div v-for="item in summoner_match.items" class="w-8 h-8 ">
          <VImg :src="urlItemHelper(item.img_url)"
                class="w-8 h-8"/>

        </div>
      </div>
    </div>
    <div class="w-64 flex flex-col justify-center space-y-1 my-2">
      <div>P/Kill {{ (summoner_match?.kill_participation * 100).toFixed(0) }}%</div>
      <div>Control Ward nc</div>
      <div>CS {{ summoner_match.minions_killed }}
          ({{ (summoner_match.minions_killed / getDurationMinutes(summoner_match.match?.match_duration)).toFixed(1) }})
      </div>
      <div>Avg Rank nc</div>
    </div>
    <div class="flex w-96 justify-self-end my-2">
      <div class="grid grid-cols-2">
        <div>
          <template v-for="participant in summoner_match.other_participants">
            <MatchesRowPart
                :key="participant.id"
                v-if="summoner_match.won == participant.won"
                :participant="participant"
                :summoner_encounter_count="summoner_encounter_count"
                :is_self="summoner_match.summoner_id === participant.summoner_id"
            />
          </template>
        </div>
        <div>
          <template v-for="participant in summoner_match.other_participants">
            <MatchesRowPart
                :key="participant.id"
                v-if="summoner_match.won != participant.won"
                :participant="participant"
                :summoner_encounter_count="summoner_encounter_count"
                :is_self="summoner_match.summoner_id === participant.summoner_id"
            />
          </template>
        </div>
      </div>
    </div>
      <div class="flex items-center mx-1">
          <PrimaryButton
              @click="navigateToMatch(summoner.id, summoner_match.id)"
              class="w-full h-8 text-sm"
              color="white"
          >Go</PrimaryButton>
      </div>
    <div class="flex justify-end ml-1  w-14">
      <div :class="`${summoner_match.won ? 'bg-blue-600' : 'bg-red-700'} w-14 flex items-end pb-4 rounded-r`">

          <VIcon
            @click="toggleIsOpen"
            :class="`${is_open ? 'transform rotate-180' : ''} ${summoner_match.won ? 'text-blue' : 'text-red'} cursor-pointer w-8 h-8 mx-auto`"
            icon="fa fa-chevron-down"/>

      </div>
    </div>
  </div>
  <MatchDetail :summoner_match="loaded_summoner_match" v-if="  is_open"/>
</template>

