<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import Pagination from "@/Components/Pagination.vue";
import {CustomChampionPaginated} from "@/types/champions_stats";
import ChampionsRow from "@/Components/Summoner/ChampionsRow.vue";


const props = defineProps<{
    champions: CustomChampionPaginated
}>();

const params_to_reload: string[] = [
    'champions',
]
</script>

<template>
    <div class="w-7/12 mx-auto my-6 text-gray-5">

        <SummonerHeader
            tab="Champions"
        />
      <div class="position-absolute w-10/12 left-40 my-4">
        <VTable>
          <thead>
          <tr>
            <th class="text-left">Champion</th>
            <th class="text-left">W-L</th>
            <th class="text-left">Win Rate</th>
            <th class="text-left">Avg KDA</th>
            <th class="text-left">Max Kills</th>
            <th class="text-left">Max Deaths</th>
            <th class="text-left">Max Assists</th>
            <th class="text-left">Avg CS</th>
            <th class="text-left">Avg Damage Dealt to Champions</th>
            <th class="text-left">Avg Damage Taken</th>
            <th class="text-left">Avg Gold</th>
            <th class="text-left">Total Double Kills</th>
            <th class="text-left">Total Triple Kills</th>
            <th class="text-left">Total Quadra Kills</th>
            <th class="text-left">Total Penta Kills</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(champion, idx) in champions.data" :key="champion.id"
              :class="(idx % 2 === 0 ? 'bg-zinc-800' : '' ) + ' hover:bg-zinc-900'">
            <ChampionsRow
                :champion="champion"
                :key="champion.id"
                version=""/>
          </tr>
          </tbody>
        </VTable>
        <Pagination :links="champions.links"/>
      </div>


    </div>

</template>

<style>

</style>
