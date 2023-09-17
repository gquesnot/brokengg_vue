<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {LolMatchInterface} from "@/types/lol-match";
import {SummonerMatchInterface} from "@/types/summoner-match";
import MatchDetail from "@/Components/Summoner/MatchDetail.vue";


const props = defineProps<{
  summoner_match:SummonerMatchInterface
}>();




</script>

<template>
  <div class="w-7/12 mx-auto my-6">

    <SummonerHeader
        tab="Matches"
    />
    <MatchDetail :summoner_match="summoner_match"/>
  </div>


</template>

<style>

</style>
