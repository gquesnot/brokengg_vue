<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";


const props = defineProps<{
  champion: ChampionInterface
}>();

</script>

<template>
  <div class="w-7/12 mx-auto my-6">

    <SummonerHeader
        tab="Champions"
    />
    Champion
  </div>


</template>

<style>

</style>
