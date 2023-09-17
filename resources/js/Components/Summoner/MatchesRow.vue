<script setup lang="ts">


import moment from 'moment';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router} from "@inertiajs/vue3";
import {urlChampionHelper, urlItemHelper} from "@/Helpers/url_helpers";
import {FiltersInterface} from "@/Types/filters";
import MatchesRowPart from "@/Components/Summoner/MatchesRowPart.vue";
import {SummonerMatchInterface} from "@/Types/summoner-match";
import {getSummoner} from "@/Helpers/root_props_helpers";
import {navigateToMatch} from "@/Helpers/router_helpers";


const props = defineProps<{
    summoner_match: SummonerMatchInterface
    summoner_encounter_count: SummonerEncounterCountInterface
}>();


const summoner = getSummoner();



</script>

<template>
    <div
        :class="`${summoner_match.won ? 'bg-blue-700' : 'bg-red-700'}  p-4 my-2 flex rounded bg-opacity-50`">
        <div class="w-1/5 flex flex-col justify-center space-y-1">
            <div>{{ summoner_match.match?.queue?.description }}</div>
            <div>{{ moment(summoner_match.match?.match_end).fromNow() }}</div>
            <div>{{ summoner_match.won ? 'Victory' : 'Defeat' }}</div>
            <div>{{ summoner_match.match?.match_duration }}</div>
        </div>
        <div class="ml-4 w-1/5 flex flex-col justify-center">
            <div class="w-full flex">
                <div class="w-16 h-16">
                    <VImg
                        :src="urlChampionHelper(summoner_match.champion?.img_url)"
                        class="w-16 h-16"/>
                </div>
                <div class="ml-4 text-xl flex justify-center items-center flex-col">
                    <div class="flex">
                        <div class="text-white font-bold">{{ summoner_match.kills }}</div>
                        <div class="text-gray-500 mx-1">/</div>
                        <div class="text-red font-bold">{{ summoner_match.deaths }}</div>
                        <div class="text-gray-500 mx-1">/</div>
                        <div class="text-white font-bold">{{ summoner_match.assists }}</div>
                    </div>
                    <div>
                        {{ summoner_match.kda?.toFixed(2) }}:1 KDA
                    </div>
                </div>
            </div>
            <div class="flex mt-4 space-x-2">
                <div v-for="item in summoner_match.items" class="w-8 h-8 ">
                    <VImg :src="urlItemHelper(item.img_url)"
                          class="w-8 h-8"/>

                </div>
            </div>
        </div>
        <div class="w-1/5 flex flex-col justify-center space-y-1">
            <div>P/Kill {{ (summoner_match?.kill_participation * 100).toFixed(0) }}%</div>
            <div>Control Ward nc</div>
            <div>CS {{ summoner_match.minions_killed }}
                ({{ (summoner_match.minions_killed / moment(summoner_match.match?.match_duration).minutes()).toFixed(1) }})
            </div>
            <div>Avg Rank nc</div>
        </div>
        <div class="flex w-1/4 justify-self-end">
            <div class="grid grid-cols-2">
                <div>
                    <template v-for="participant in summoner_match.other_participants">
                        <MatchesRowPart
                            :key="participant.id"
                            v-if="summoner_match.won == participant.won"
                            :participant="participant"
                            :summoner_encounter_count="summoner_encounter_count"
                            :is_self="summoner_match.summoner_id === participant.summoner_id"
                        />
                    </template>
                </div>
                <div>
                    <template v-for="participant in summoner_match.other_participants">
                        <MatchesRowPart
                            :key="participant.id"
                            v-if="summoner_match.won != participant.won"
                            :participant="participant"
                            :summoner_encounter_count="summoner_encounter_count"
                            :is_self="summoner_match.summoner_id === participant.summoner_id"
                        />
                    </template>
                </div>
            </div>
        </div>
        <div class="flex justify-center ml-6 items-center w-1/5">
            <PrimaryButton @click="navigateToMatch(summoner.id, summoner_match.match_id)">Go</PrimaryButton>

        </div>
    </div>
</template>

