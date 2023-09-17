<script setup lang="ts">


import {router} from "@inertiajs/vue3";
import {urlChampionHelper} from "@/helpers/url_helpers";
import {getSummoner} from "@/helpers/root_props_helpers";
import {navigateToEncounter} from "@/helpers/router_helpers";


const props = defineProps<{
    is_my_team: boolean
    participant: any
}>();


const summoner = getSummoner();

</script>

<template>
    <div
         :class="`flex pl-2  border-l-4 py-1 w-1/2 ${is_my_team ? 'border-blue-500' : 'border-red-500'}`">
        <div class="w-8 h-8" v-if="participant['champion']">
            <VImg
                :src="urlChampionHelper(participant['champion']['img_url'])"
                class="w-8 h-8"/>
        </div>
        <div class="w-25 truncate ml-4">{{ participant['summoner']['name'] }}</div>
        <div class="ml-4 w-10">
            <div v-if="participant['encounter_count']">
                <div @click="navigateToEncounter(summoner.id, participant['summoner']['id'])" class="cursor-pointer">
                    {{ participant['encounter_count'] }}
                </div>
            </div>
            <div v-else>
                0
            </div>
        </div>

    </div>
</template>

<style>

</style>
