<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";


const props = defineProps<{
  match: LolMatchInterface
}>();


</script>

<template>
  <div class="w-10/12 mx-auto my-6">

    <SummonerHeader
        tab="Matches"
    />
    Match
  </div>


</template>

<style>

</style>
