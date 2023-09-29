<script setup lang="ts">


import {PropType} from "vue";
import {urlProfilIconHelper, urlProPlayerHelper} from "@/helpers/url_helpers";
import {SummonerStatsInterface} from "@/types/summoner_stats";
import {navigateToSummoner} from "@/helpers/router_helpers";
import {SummonerInterface} from "@/types/summoner";


const props = defineProps({
    summoner_stats: {
        type: Object as PropType<SummonerStatsInterface>,
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
        :class="`text-gray-5 bg-gray-1 text-gray-1 flex justify-${justify} rounded my-3  p-4 ${is_reverse ? 'flex-row-reverse ' : ''}`">
      <div v-if="with_summoner_name" :class="`mx-4 flex items-center ${!is_reverse ? 'flex-row-reverse ' : ''}`">
        <div>
          <div class="text-xl font-bold cursor-pointer mx-2 flex flex-col" @click="navigateToSummoner(summoner.id)">
            {{ summoner.name }}
          </div>
          <div class="flex ml-1 justify-center text-xs" v-if="summoner.pro_player">
            <a :href="urlProPlayerHelper(summoner.pro_player.slug)">
              <div class="bg-purple-800 py-0.5 px-1 rounded">
                PRO
                <v-tooltip
                    activator="parent"
                    location="bottom"
                    class="text-center"
                >
                  <p>
                    {{ summoner.pro_player?.team_name }}
                  </p>
                  <p>
                    {{ summoner.pro_player?.name }}
                  </p>
                </v-tooltip>
              </div>
            </a>
          </div>
            </div>

        <VImg :src="urlProfilIconHelper(summoner.profile_icon_id)" class="w-16 h-16 rounded-full mx-1"/>
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
                <div class="text-gray-5 font-bold">{{ summoner_stats.avg_kills.toFixed(1) }}</div>
                <div class="text-gray-4 mx-1">/</div>
                <div class="text-red-3 font-bold">{{ summoner_stats.avg_deaths.toFixed(1) }}</div>
                <div class="text-gray-4 mx-1">/</div>
                <div class="text-gray-5 font-bold">{{ summoner_stats.avg_assists.toFixed(1) }}</div>
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
