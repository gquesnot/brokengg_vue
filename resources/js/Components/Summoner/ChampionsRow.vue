<script setup lang="ts">

import {router} from "@inertiajs/vue3";
import {urlChampionHelper} from "@/helpers/url_helpers";
import {ChampionStatsInterface} from "@/types/champions_stats";
import {computed} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {getSummoner} from "@/helpers/root_props_helpers";



const props = defineProps<{
  champion: ChampionStatsInterface
  version: string
}>();

const winRate = computed(() => (props.champion.wins / props.champion.total * 100).toFixed(0));
const loses = computed(() => (props.champion.total - props.champion.wins));

const navigateToChampion = () => {
  router.visit(route('summoner.champion', {
      summoner:getSummoner().id,
      champion: props.champion.champion_id,
  }), {
    preserveState: true,
  })
}

</script>

<template>
  <td>
    <div class="flex items-center w-50">
      <VImg
          :src="urlChampionHelper(props.champion.champion.img_url)"
          class="h-12 w-12 rounded"/>
      <div class="ml-2">
        {{ props.champion.champion.name }}
      </div>
    </div>

  </td>
  <td>
    <div>
      <div>{{ props.champion.wins }}W</div>
      <div> {{ loses }}L</div>
    </div>
  </td>
  <td>
    {{ winRate }}%
  </td>
  <td>{{ props.champion.avg_kills }}</td>
  <td>{{ props.champion.avg_deaths }}</td>
  <td>{{ props.champion.avg_assists }}</td>
  <td>{{ ((props.champion.avg_kills + props.champion.avg_assists) / props.champion.avg_deaths).toFixed(2) }}:1</td>
  <td>{{ props.champion.max_kills }}</td>
  <td>{{ props.champion.max_deaths }}</td>
  <td>{{ props.champion.max_assists }}</td>
  <td>{{ props.champion.avg_cs }}</td>
  <td>{{ (props.champion.avg_damage_dealt_to_champions / 1000).toFixed(1) }}k</td>
  <td>{{ (props.champion.avg_damage_taken / 1000).toFixed(1) }}k</td>
  <td>{{ (props.champion.avg_gold / 1000).toFixed(1) }}k</td>
  <td>{{ props.champion.total_double_kills }}</td>
  <td>{{ props.champion.total_triple_kills }}</td>
  <td>{{ props.champion.total_quadra_kills }}</td>
  <td>{{ props.champion.total_penta_kills }}</td>
  <td>
    <PrimaryButton @click="navigateToChampion">Go</PrimaryButton>
  </td>
</template>

<style>

</style>
