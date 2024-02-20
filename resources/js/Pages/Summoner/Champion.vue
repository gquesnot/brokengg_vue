<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import {useFiltersStore, useSummonerStore} from "@/store";
import {BeforeFiltersInterface} from "@/types/filters";
import {SummonerInterface} from "@/types/summoner";
import {ChampionInterface} from "@/types/champion";
import {OptionInterface} from "@/types/option";


const props = defineProps<{
    champion: ChampionInterface,
    summoner: SummonerInterface,
    filters: BeforeFiltersInterface,
    version: string,
    champion_options: OptionInterface[],
    queue_options: OptionInterface[],
    only: string[],
}>();
const summonerStore = useSummonerStore();
const filtersStore = useFiltersStore();
summonerStore.setStore(props.summoner, props.version, props.champion_options, props.queue_options, props.only);
filtersStore.setStore(props.filters);

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
