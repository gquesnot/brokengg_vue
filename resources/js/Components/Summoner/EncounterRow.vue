<script setup lang="ts">


import {PropType} from "vue";
import {urlChampionHelper} from "@/helpers/url_helpers";
import {SummonerMatchInterface} from "@/types/summoner-match";


const props = defineProps({
    summoner_match: {
        type: Object as PropType<SummonerMatchInterface> | null,
        required: true
    },
    is_reverse: {
        type: Boolean as PropType<boolean>,
        default: false,
        required: false
    }
});



</script>

<template>
    <div :class="`${props.summoner_match?.won ? 'bg-blue-500' : 'bg-red-500'} flex items-center justify-start p-2 ${is_reverse ? 'flex-row-reverse ' : ''}`" v-if="summoner_match !== null">
        <div>
            <VImg :src="urlChampionHelper(summoner_match.champion?.img_url)" class="w-16 h-16"/>
        </div>
        <div class="mx-4">
            <div class="flex">
                <div class="text-white font-bold">{{ summoner_match.kills.toFixed(1) }}</div>
                <div class="text-white mx-1">/</div>
                <div class="text-black font-bold">{{ summoner_match.deaths.toFixed(1) }}</div>
                <div class="text-white mx-1">/</div>
                <div class="text-white font-bold">{{ summoner_match.assists.toFixed(1) }}</div>
            </div>
            <div class="font-bold text-2xl">
                {{ summoner_match.kda?.toFixed(2) }}:1 KDA
            </div>
            <div>
                P/Kill {{ (summoner_match.kill_participation * 100).toFixed(0) }}%
            </div>
        </div>
    </div>
</template>

<style>

</style>
