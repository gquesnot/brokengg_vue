<script setup lang="ts">


import {PropType} from "vue";
import {urlProfilIconHelper} from "@/helpers/url_helpers";
import {SummonerStatsInterface} from "@/types/summoner_stats";
import {navigateToSummoner} from "@/helpers/router_helpers";


const props = defineProps({
    summoner_stats: {
        type: Object as PropType<SummonerStatsInterface | null>,
        required: true
    },
    summoner: {
        type: Object as PropType<SummonerInterface>,
        required: true
    },
    color: {
        type: String as PropType<string>,
        default: 'blue',
        required: false
    },
    is_reverse: {
        type: Boolean as PropType<boolean>,
        default: false,
        required: false
    },
    justify: {
        type: String as PropType<string>,
        default: 'end',
        required: false
    },
    with_summoner_name: {
        type: Boolean as PropType<boolean>,
        default: false,
        required: false
    }
})


</script>

<template>
    <div
        :class="`bg-gray-700 text-gray-200 flex justify-${justify} rounded my-3  p-4 ${is_reverse ? 'flex-row-reverse ' : ''}`"
        v-if="summoner_stats !== null">
        <div v-if="with_summoner_name" :class="`mx-4 flex items-center ${is_reverse ? 'flex-row-reverse ' : ''}`">
            <VImg :src="urlProfilIconHelper(summoner.profile_icon_id)" class="w-16 h-16 rounded-full"/>
            <div class="text-xl font-bold cursor-pointer mx-2" @click="navigateToSummoner(summoner.id)">{{
                    summoner.name
                }}
            </div>
        </div>
        <div class=" mx-4 flex flex-col items-center justify-center">
            <div class="flex space-x-2">

                <div>{{ summoner_stats.total_game }}G</div>
                <div>{{ summoner_stats.total_win }}W</div>
                <div>{{ summoner_stats.total_game - summoner_stats.total_win }}L</div>
            </div>
            <div>
                {{
                    summoner_stats.total_game === 0 ? 0 : (summoner_stats.total_win / summoner_stats.total_game * 100).toFixed(0)
                }}%
                Win Rate
            </div>
        </div>

        <div class="mx-4 flex flex-col items-center justify-center">
            <div class="flex">
                <div class="text-white font-bold">{{ summoner_stats.avg_kills.toFixed(1) }}</div>
                <div class="text-white mx-1">/</div>
                <div class="text-red-700 font-bold">{{ summoner_stats.avg_deaths.toFixed(1) }}</div>
                <div class="text-white mx-1">/</div>
                <div class="text-white font-bold">{{ summoner_stats.avg_assists.toFixed(1) }}</div>
            </div>
            <div class="font-bold text-xl">
                {{ summoner_stats.avg_kda?.toFixed(2) }}:1 KDA
            </div>
            <div>
                P/Kill {{ (summoner_stats.avg_kill_participation * 100).toFixed(0) }}%
            </div>
        </div>

    </div>
</template>

<style>

</style>
