<script setup lang="ts">


import {urlChampionHelper, urlProPlayerHelper} from "@/helpers/url_helpers";
import {getSummoner} from "@/helpers/root_props_helpers";
import {navigateToEncounter} from "@/helpers/router_helpers";


const props = defineProps<{
    is_my_team: boolean
    participant: any
}>();
console.log(props.participant)


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
            <div v-if="participant['summoner']['id'] != null">
              <div @click="navigateToEncounter(summoner.id, participant['summoner']['id'])"
                   class="cursor-pointer">
                    {{ participant['encounter_count'] }}
                </div>
            </div>
            <div v-else>
                0
            </div>
        </div>
      <div class="flex ml-1 justify-center text-xs"
           v-if="participant['summoner']['id'] != null && participant['summoner']?.pro_player">
        <a :href="urlProPlayerHelper(participant['summoner'].pro_player.slug)">
          <div class="bg-purple-800 py-0.5 px-1 rounded">
            PRO
            <v-tooltip
                activator="parent"
                location="bottom"
                class="text-center"
            >
              <p>
                {{ participant['summoner'].pro_player?.team_name }}
              </p>
              <p>
                {{ participant['summoner'].pro_player?.name }}
              </p>
            </v-tooltip>
          </div>
        </a>
      </div>

    </div>
</template>

<style>

</style>
