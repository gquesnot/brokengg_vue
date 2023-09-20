<script setup lang="ts">


import {SummonerMatchInterface} from "@/types/summoner_match";


const props = defineProps<{
  participants: SummonerMatchInterface[],
  key_stat: string,
  has_won: boolean,
  description: string,
  height?: string,
}>();

let my_team_stat: number = 0;
let other_team_stat: number = 0;

for (let participant of props.participants) {
  if (participant.won === props.has_won) {
    my_team_stat += participant[props.key_stat]
  } else {
    other_team_stat += participant[props.key_stat]
  }
}


</script>

<template>
  <div class="flex relative items-center h-full">
    <div :class="`${has_won ? 'bg-red-3' : 'bg-blue-3'} flex items-center justify-end text-right w-full pr-3 ${height}`">
      <strong>
        {{ other_team_stat }}
      </strong>
    </div>
    <div :class="`${has_won ? 'bg-blue-3' : 'bg-red-3'} absolute text-left left-0 pl-3 flex items-center justify-start ${height}`"
         :style="{width:  my_team_stat/ (my_team_stat + other_team_stat) * 100 + '%'}">
      {{ my_team_stat }}
    </div>
    <div class="absolute text-center w-full">
      {{ description }}
    </div>
  </div>
</template>

