<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner-match";
import {getSummoner} from "@/helpers/root_props_helpers";
import {urlChampionHelper, urlItemHelper} from "@/helpers/url_helpers";


const props = defineProps<{
    participants: SummonerMatchInterface[] | null|undefined,
    duration_minutes: number,
    won: boolean
}>();


const summoner = getSummoner();

const getTrColor = (participant: SummonerMatchInterface) => {
    if (summoner.id === participant.summoner_id) {
        return participant.won ? 'bg-blue-900' : 'bg-red-800'
    } else {
        return participant.won ? 'bg-blue-950' : 'bg-red-900'
    }
}

let max_total_damage_dealt:number = 0;
let max_total_damage_taken:number = 0;
if (props.participants){
    for (let participant of props.participants) {
        if (participant.total_damage_dealt_to_champions > max_total_damage_dealt) {
            max_total_damage_dealt = participant.total_damage_dealt_to_champions
        }
        if ( participant.total_damage_taken > max_total_damage_taken) {
            max_total_damage_taken = participant.total_damage_taken
        }
    }
}



</script>

<template>
    <table class="text-gray-200 ">
        <thead>
        <tr>
            <th :class="`${won ? 'text-blue-600' : 'text-red-600' } font-bold p-2`">
                {{ won ? 'Victory' : 'Defeat' }}
            </th>
            <th class="p-2">KDA</th>
            <th class="p-2">Damage</th>
            <th class="p-2">CS</th>
            <th class="p-2">Item</th>
        </tr>
        </thead>
        <tbody >
        <template v-for="(participant, index) in participants " :key="participant.id">
            <tr :class="`${getTrColor(participant)} border-gray-500 border-b-2 text-left`"
                v-if="participant.won === won">
                <td class="w-64  px-3">
                    <div class="flex items-center justify-start  pl-2 space-x-2">
                        <div class="w-fit">
                            <VImg
                                :alt="participant.champion?.name ?? ''"
                                :src="urlChampionHelper( participant.champion?.img_url)"
                                class="w-12 h-12 rounded-full"/>
                        </div>
                        <div>
                            <!--                                summoner spells-->
                        </div>
                        <div class="flex flex-col font-medium">
                            <div>{{ participant?.summoner?.name }}</div>
                            <div>
                                {{ participant?.summoner?.summoner_level }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="w-64 py-1  px-3">
                    <div class="ml-4 text-xl flex justify-center items-center flex-col">
                        <div class="flex">
                            <div class="text-gray-200 font-bold">{{ participant.kills }}</div>
                            <div class="text-gray-500 mx-1">/</div>
                            <div class="text-red font-bold">{{ participant.deaths }}</div>
                            <div class="text-gray-500 mx-1">/</div>
                            <div class="text-gray-200 font-bold">{{ participant.assists }}</div>
                        </div>
                        <div>
                            {{ participant.kda?.toFixed(2) }}:1 KDA
                        </div>
                    </div>
                </td>
                <td class="w-fit py-1 px-3">
                    <div class="flex w-48 text-center">
                        <div class="w-24">
                            {{participant.total_damage_dealt_to_champions}}
                            <VProgressLinear :model-value="Math.round((participant.total_damage_dealt_to_champions / max_total_damage_dealt) * 100)" color="error"
                                             :height="10" />
                        </div>
                        <div class="ml-4 w-24">
                            {{participant.total_damage_taken}}
                            <VProgressLinear :model-value="Math.round((participant.total_damage_taken / max_total_damage_taken) * 100)" color="dark-blue"
                                             :height="10" />
                        </div>


                    </div>
                </td>
                <td class="w-fit py-1 px-3">
                    <div class="text-center">
                        <div>{{participant.minions_killed}}</div>
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

