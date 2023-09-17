<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import Pagination from "@/Components/Pagination.vue";
import SummonerStats from "@/Components/Summoner/SummonerStats.vue";
import MatchesRow from "@/Components/Summoner/MatchesRow.vue";
import {SummonerMatchesPaginated} from "@/types/summoner-match";
import {SummonerStatsInterface} from "@/types/summoner_stats";
import {getSummoner} from "@/helpers/root_props_helpers";


const props = defineProps<{
  matches: SummonerMatchesPaginated
  summoner_stats: SummonerStatsInterface
  summoner_encounter_count: SummonerEncounterCountInterface
}>();


const summoner = getSummoner();


</script>

<template>

  <div class="w-7/12 mx-auto my-6">

    <SummonerHeader
        tab="Matches"
    />

    <SummonerStats :summoner_stats="summoner_stats" :summoner="summoner" justify="start"/>
    <div class="flex flex-col" v-for="match in matches.data" :key="match.id">
      <MatchesRow
          :key="match.id"
          :summoner_match="match"
          :summoner_encounter_count="summoner_encounter_count"
      />
    </div>
    <Pagination :links="matches.links"
    />
  </div>


</template>

<style>

</style>
