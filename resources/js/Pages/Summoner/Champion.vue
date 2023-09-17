<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {FiltersInterface} from "@/types/filters";


const props = defineProps<{
    champion: ChampionInterface
}>();

</script>

<template>
    <div class="w-10/12 mx-auto my-6">

        <SummonerHeader
            tab="Champions"
        />
        Champion
    </div>


</template>

<style>

</style>
