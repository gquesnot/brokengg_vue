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


const handleSplitNumberToString = (number:any) => {
  // if number is number
    if (props.split_number) {
        number = (number / 1000).toFixed(2)
    }
    return number.toString().replace('.', ',') + (props.split_number ? 'k' : '')
}

const getParticipantStat = (participant : SummonerMatchInterface): number  => {
  let stat  = participant[props.key_stat as keyof SummonerMatchInterface]
  if (typeof  stat == 'number'){
    return stat
  } else {
    return 0
  }
}



let max_stat: number = 0;
let won_stat:number = 0;
let lose_stat:number = 0;

for (let participant of props.participants) {
  if (getParticipantStat(participant) > max_stat) {
    max_stat = getParticipantStat(participant)
  }
  if (participant.won) {
    won_stat += getParticipantStat(participant)
  } else {
    lose_stat += getParticipantStat(participant)
  }
}




let chart_data: ChartData<"doughnut", number[], unknown> = {
  datasets: [
    {
        data: [handleSplitNumberToNumber(lose_stat), handleSplitNumberToNumber(won_stat)],
      backgroundColor: [
          '#E57373',
        '#64B5F6',
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
                  :model-value="(getParticipantStat(participant) / max_stat) * 100"
                  color="blue-lighten-2"
                  :height="25">
                <strong class="ml-auto mr-3">{{ handleSplitNumberToString(getParticipantStat(participant)) }}</strong>
              </VProgressLinear>
            </div>

          </div>
        </template>

      </div>
        <div class="w-1/3 h-full">
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
                  :model-value="(getParticipantStat(participant) / max_stat) * 100"
                  color="red-lighten-2"
                  :height="25">
                <strong class="ml-auto mr-3">{{ handleSplitNumberToString(getParticipantStat(participant)) }}</strong>
              </VProgressLinear>
            </div>



          </div>
        </template>
      </div>

    </div>

  </div>
</template>

