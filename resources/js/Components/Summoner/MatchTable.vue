<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";
import {getSummoner} from "@/helpers/root_props_helpers";
import {urlChampionHelper, urlItemHelper, urlPerkHelper, urlSummonerSpellHelper} from "@/helpers/url_helpers";
import {navigateToEncounter, navigateToSummoner} from "@/helpers/router_helpers";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";


const props = defineProps<{
    participants: SummonerMatchInterface[],
    summoner_encounter_count: SummonerEncounterCountInterface,
    duration_minutes: number,
    won: boolean
}>();


const summoner = getSummoner();

const getTrColor = (participant: SummonerMatchInterface) => {
    if (summoner.id === participant.summoner_id) {
        return participant.won ? 'bg-blue-2' : 'bg-red-2'
    } else {
        return participant.won ? 'bg-blue-1' : 'bg-red-1'
    }
}

let max_total_damage_dealt: number = 0;
let max_total_damage_taken: number = 0;
for (let participant of props.participants) {
    if (participant.total_damage_dealt_to_champions > max_total_damage_dealt) {
        max_total_damage_dealt = participant.total_damage_dealt_to_champions
    }
    if (participant.total_damage_taken > max_total_damage_taken) {
        max_total_damage_taken = participant.total_damage_taken
    }
}

</script>

<template>
    <table class="text-gray-5">
        <thead>
        <tr>
            <th :class="`${won ? 'text-blue-3' : 'text-red-3' } font-bold p-2`">
                {{ won ? 'Victory' : 'Defeat' }}
            </th>
            <th class="p-2">Seen</th>
            <th class="p-2">KDA</th>
            <th class="p-2">Damage</th>
            <th class="p-2">CS</th>
            <th class="p-2">Item</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="(participant, index) in participants " :key="participant.id">
            <tr :class="`${getTrColor(participant)} border-gray-500 border-b-2 text-left`"
                v-if="participant.won === won">
                <td class="w-64  px-3">
                    <div class="flex items-center justify-start  pl-2 space-x-2">
                        <div class="w-12 h-12 relative">
                            <VImg
                                :src="urlChampionHelper(participant.champion?.img_url)"
                                class="w-12 h-12 rounded-full"/>
                            <div class="absolute -bottom-1 right-0 bg-gray-1 rounded-full px-0.5">
                                {{participant.champ_level}}
                            </div>
                        </div>
                        <div class="flex flex-col ml-1">
                            <VImg
                                :src="urlSummonerSpellHelper(participant.summoner_spell1.img_url)"
                                class="w-6 h-6 rounded"/>
                            <VImg
                                :src="urlSummonerSpellHelper(participant.summoner_spell2.img_url)"
                                class="w-6 h-6 rounded mt-1"/>


                        </div>
                        <div class="flex flex-col ml-1">
                            <VImg
                                :src="urlPerkHelper(participant.perks.primary_style1.img_url)"
                                class="w-6 h-6 bg-black rounded-full"/>
                            <VImg
                                :src="urlPerkHelper(participant.perks.sub_style.img_url)"
                                class="w-6 h-6 mt-1 "/>
                        </div>
                        <div class="flex flex-col font-medium">
                            <div class="cursor-pointer" @click="navigateToSummoner(participant.summoner_id)">{{ participant?.summoner?.name }}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <template v-if="participant.summoner_id === summoner.id">
                        <div class="flex items-center justify-center">
                            <VIcon icon="fa fa-user-o" class="w-3 h-3"/>
                        </div>

                    </template>
                    <template v-else>
                        <a href="#"
                           @click.prevent="navigateToEncounter(summoner.id, participant.summoner_id)">

                            <template
                                v-if="summoner_encounter_count.hasOwnProperty(participant.summoner_id)">
                                {{ summoner_encounter_count[participant.summoner_id] }}
                            </template>
                            <template v-else>
                                1
                            </template>
                        </a>

                    </template>
                </td>
                <td class="w-64 py-1  px-3">
                    <div class="ml-4 text-xl flex justify-center items-center flex-col">
                        <div class="flex">
                            <div class="text-gray-5 font-bold">{{ participant.kills }}</div>
                            <div class="text-gray-4 mx-1">/</div>
                            <div class="text-red-3 font-bold">{{ participant.deaths }}</div>
                            <div class="text-gray-4 mx-1">/</div>
                            <div class="text-gray-5 font-bold">{{ participant.assists }}</div>
                        </div>
                        <div>
                            {{ participant.kda?.toFixed(2) }}:1 KDA
                        </div>
                    </div>
                </td>
                <td class="w-fit py-1 px-3">
                    <div class="flex w-48 text-center">
                        <div class="w-24">
                            {{ participant.total_damage_dealt_to_champions }}
                            <VProgressLinear
                                :model-value="Math.round((participant.total_damage_dealt_to_champions / max_total_damage_dealt) * 100)"
                                color="red-lighten-2"
                                :height="10"/>
                        </div>
                        <div class="ml-4 w-24">
                            {{ participant.total_damage_taken }}
                            <VProgressLinear
                                :model-value="Math.round((participant.total_damage_taken / max_total_damage_taken) * 100)"
                                color="blue-grey-lighten-1"
                                :height="10"/>
                        </div>


                    </div>
                </td>
                <td class="w-fit py-1 px-3">
                    <div class="text-center">
                        <div>{{ participant.minions_killed }}</div>
                        <div>{{ (participant.minions_killed / duration_minutes).toFixed(1) }}/m</div>
                    </div>
                </td>
                <td class=" py-1 w-72 px-3">
                    <div class="flex space-x-2">
                        <div v-for="item in participant.items" class="w-8 h-8">
                            <VImg :src="urlItemHelper(item.img_url)"
                                  class="w-8 h-8"/>
                        </div>

                    </div>

                </td>
            </tr>
        </template>
        </tbody>
    </table>

</template>

