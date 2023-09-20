<script setup lang="ts">


import {SummonerMatchInterface} from "@/types/summoner_match";
import {urlChampionHelper} from "@/helpers/url_helpers";
import {Doughnut} from "vue-chartjs";
import {ChartData} from "chart.js";


const props = defineProps<{
  participants: SummonerMatchInterface[],
  key_stat: string,
  description: string,
  split_number:boolean,
}>();

const handleSplitNumberToNumber = (number:number) => {
  if (props.split_number) {
    return number / 1000
  } else {
    return number
  }
}


const handleSplitNumberToString = (number:number) => {
  if (props.split_number) {
    return number.toString().replace('.', ',')
  } else {
    return number
  }
}


let max_stat: number = 0;
let won_stat:number = 0;
let lose_stat:number = 0;

for (let participant of props.participants) {
  if (participant[props.key_stat] > max_stat) {
    max_stat = participant[props.key_stat]
  }
  if (participant.won) {
    won_stat += participant[props.key_stat]
  } else {
    lose_stat += participant[props.key_stat]
  }
}



let chart_data: ChartData<"doughnut", number[], unknown> = {
  datasets: [
    {
      data: [handleSplitNumberToNumber(won_stat) ,handleSplitNumberToNumber(lose_stat)],
      backgroundColor: [
        '#64B5F6',
        '#E57373',
      ],
    }
  ]
}




</script>

<template>
  <div class="flex flex-col justify-center p-2">
    <div class="text-center">{{description}}</div>
    <div class="flex space-x-2 mt-2">
      <div class="w-1/3 flex flex-col space-y-1">
        <template v-for="(participant, idx) in participants" :key="participant.id">
          <div class="flex items-center "  v-if="participant.won">
            <div>
              <VImg
                :src="urlChampionHelper(participant.champion.img_url)"
                class="w-6 h-6"/>
            </div>
            <div class="w-full ml-1">
              <VProgressLinear
                  :model-value="(participant[key_stat] / max_stat) * 100"
                  color="blue-lighten-2"
                  :height="25">
                <strong class="ml-auto mr-3">{{ handleSplitNumberToString(participant[key_stat]) }}</strong>
              </VProgressLinear>
            </div>

          </div>
        </template>

      </div>
      <div class="w-1/3">
        <Doughnut :data="chart_data" :options="{responsive:true, maintainAspectRatio:false}" />
      </div>
      <div class="w-1/3 flex flex-col space-y-1">
        <template v-for="(participant, idx) in participants" :key="participant.id">
          <div class="flex items-center"  v-if="!participant.won">
            <div>
              <VImg
                  :src="urlChampionHelper(participant.champion.img_url)"
                  class="w-6 h-6"/>
            </div>
            <div class="w-full ml-1">
              <VProgressLinear
                  :model-value="(participant[key_stat] / max_stat) * 100"
                  color="red-lighten-2"
                  :height="25">
                <strong class="ml-auto mr-3">{{ handleSplitNumberToString(participant[key_stat]) }}</strong>
              </VProgressLinear>
            </div>



          </div>
        </template>
      </div>

    </div>

  </div>
</template>

