<script setup lang="ts">


import moment from 'moment';
import {urlChampionHelper, urlItemHelper, urlPerkHelper, urlSummonerSpellHelper} from "@/helpers/url_helpers";
import MatchesRowPart from "@/Components/Summoner/MatchesRowPart.vue";
import {SummonerMatchInterface} from "@/types/summoner_match";
import {getSummoner} from "@/helpers/root_props_helpers";
import {onMounted, ref} from "vue";
import MatchDetail from "@/Components/Summoner/MatchDetail.vue";
import {getDurationMinutes} from "@/helpers/duration_helpers";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {navigateToMatch} from "@/helpers/router_helpers";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";
import axios from "axios";
import {usePage} from "@inertiajs/vue3";
import {getAvgRankString} from "../../helpers/league_helpers";


const props = defineProps<{
  summoner_match: SummonerMatchInterface
  summoner_encounter_count: SummonerEncounterCountInterface
  auto_open?: boolean
}>();

onMounted(() => {
  if (props.auto_open) {
    toggleIsOpen()
  }
})

let is_open = ref(false);

const summoner = getSummoner();
const loaded_summoner_match = ref<SummonerMatchInterface | null>(null);


const toggleIsOpen = () => {
  is_open.value = !is_open.value
  if (is_open.value && !loaded_summoner_match.value) {
    axios.get(route('summoner.match.loaded', {
      summoner_match_id: props.summoner_match.id,
      summoner_id: getSummoner().id
    }))
        .then(response => {
          loaded_summoner_match.value = response.data.summoner_match
        })
        .catch(error => {
        })
  }
}


</script>

<template>
  <div
      :class="`${summoner_match.won ? 'bg-blue-1' : 'bg-red-1'}  my-1.5 flex rounded opacity-95 text-gray-200`">
    <div :class="`${summoner_match.won ? 'bg-blue-2' : 'bg-red-2'} w-3 rounded-l`"></div>
      <div class="w-32 flex flex-col justify-center space-y-1 my-2 pl-4">
      <div>{{ summoner_match.match?.queue?.description.replace('games', '') }}</div>
      <div>{{ moment(summoner_match.match?.match_end).fromNow() }}</div>
      <div>{{ summoner_match.won ? 'Victory' : 'Defeat' }}</div>
      <div>{{ summoner_match.match?.match_duration }}</div>
    </div>
    <div class="ml-4 w-64 flex flex-col justify-center my-2">
      <div class="w-full flex">
        <div class="w-16 h-16 relative">
          <VImg
              :src="urlChampionHelper(summoner_match.champion?.img_url)"
              class="w-16 h-16 rounded-full"/>
            <div class="absolute -bottom-1 right-0 bg-gray-1 rounded-full px-0.5">
                {{summoner_match.champ_level}}
            </div>
        </div>
          <div class="flex flex-col ml-1">
              <VImg
                  :src="urlSummonerSpellHelper(summoner_match.summoner_spell1.img_url)"
                  class="w-7 h-7 rounded"/>
              <VImg
                  :src="urlSummonerSpellHelper(summoner_match.summoner_spell2.img_url)"
                  class="w-7 h-7 rounded mt-1"/>


          </div>
          <div class="flex flex-col ml-1">
              <VImg
                  :src="urlPerkHelper(summoner_match.perks.primary_selection.img_url)"
                  class="w-7 h-7 bg-black rounded-full"/>
              <VImg
                  :src="urlPerkHelper(summoner_match.perks.sub_style.img_url)"
                  class="w-7 h-7 mt-1 "/>
          </div>
        <div class="ml-4 text-xl flex justify-center items-center flex-col">
          <div class="flex">
            <div class="text-gray-5 font-bold">{{ summoner_match.kills }}</div>
            <div class="text-gray-4 mx-1">/</div>
            <div class="text-red-4 font-bold">{{ summoner_match.deaths }}</div>
            <div class="text-gray-4 mx-1">/</div>
            <div class="text-gray-5 font-bold">{{ summoner_match.assists }}</div>
          </div>
          <div>
            {{ summoner_match.kda?.toFixed(1) }}:1 KDA
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
    <div class="w-48 flex flex-col justify-center space-y-1 my-2 ml-6">
      <div>P/Kill {{ (summoner_match?.kill_participation * 100).toFixed(0) }}%</div>
      <div>Control Ward nc</div>
      <div>CS {{ summoner_match.minions_killed }}
        ({{
          (summoner_match.minions_killed / getDurationMinutes(summoner_match.match?.match_duration)).toFixed(1)
        }})
      </div>
      <div>{{ getAvgRankString(summoner_match.match.participants) }}</div>
    </div>
    <div class="flex w-96 justify-self-end my-2">
      <div class="grid grid-cols-2">
        <div>
          <template v-for="participant in summoner_match.match.participants">
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
          <template v-for="participant in summoner_match.match.participants">
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
          v-if="!auto_open"
          @click="navigateToMatch(summoner.id, summoner_match.id)"
          class="w-full h-8 text-sm"
          color="white"
      >Go
      </PrimaryButton>
      <div v-else class="w-14">
      </div>

    </div>
    <div class="flex justify-end ml-1  w-14">
      <div
          :class="`${summoner_match.won ? 'bg-blue-2 text-blue-4' : 'bg-red-2 text-red-4'} w-14 flex items-end pb-4 rounded-r`">

        <VIcon
            @click="toggleIsOpen"
            :class="`${is_open ? 'transform rotate-180' : ''} cursor-pointer w-8 h-8 mx-auto`"
            icon="fa fa-chevron-down"/>

      </div>
    </div>
  </div>

  <MatchDetail :summoner_match="loaded_summoner_match" :summoner_encounter_count="summoner_encounter_count"
               v-if="is_open && loaded_summoner_match"/>
  <template v-else-if="is_open">
    <div class="w-full h-32 flex justify-center items-center bg-gray-1">
      <v-progress-circular
          :size="70"
          :width="7"
          color="black"
          indeterminate
      ></v-progress-circular>
    </div>
  </template>
</template>

