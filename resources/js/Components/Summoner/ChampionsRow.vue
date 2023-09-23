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

const winRate = computed(() => (props.champion.total_win / props.champion.total * 100).toFixed(0));
const loses = computed(() => (props.champion.total - props.champion.total_win));

const navigateToChampion = () => {
    router.visit(route('summoner.champion', {
      summoner_id: getSummoner().id,
      champion_id: props.champion.champion_id,
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
          <div>{{ props.champion.total_win }}W</div>
            <div> {{ loses }}L</div>
        </div>
    </td>
    <td>
        {{ winRate }}%
    </td>
    <td>
      <div class="flex flex-col w-36 items-center">
        <div class="flex">
          {{ props.champion.avg_kills }} / {{ props.champion.avg_deaths }} /
                {{ props.champion.avg_assists }}
            </div>
            <div> {{
                    ((props.champion.avg_kills + props.champion.avg_assists) / props.champion.avg_deaths).toFixed(2)
                }}:1
            </div>
        </div>
    </td>
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
