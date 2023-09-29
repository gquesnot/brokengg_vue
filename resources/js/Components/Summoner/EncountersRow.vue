<script setup lang="ts">


import PrimaryButton from "@/Components/PrimaryButton.vue";
import {EncounterInterface} from "@/types/summoner_encounters";
import {navigateToEncounter} from "@/helpers/router_helpers";
import {getSummoner} from "@/helpers/root_props_helpers";
import {urlProPlayerHelper} from "@/helpers/url_helpers";


const props = defineProps<{
    encounter: EncounterInterface
}>();

const summoner = getSummoner();

</script>

<template>
    <td>
        {{ encounter.name }}
    </td>
    <td>
      <div class="flex items-center">
        <div>
          {{ encounter.encounter_count }}
        </div>
        <div class="flex ml-1 justify-center text-xs ml-2" v-if="encounter.summoner.pro_player">
          <a :href="urlProPlayerHelper(encounter.summoner.pro_player.slug)">
            <div class="bg-purple-800 py-0.5 px-1 rounded">
              PRO
              <v-tooltip
                  activator="parent"
                  location="bottom"
                  class="text-center"
              >
                <p>
                  {{ encounter.summoner.pro_player?.team_name }}
                </p>
                <p>
                  {{ encounter.summoner.pro_player?.name }}
                </p>
              </v-tooltip>
            </div>
          </a>
        </div>
      </div>
    </td>

  <td>
        <PrimaryButton @click="navigateToEncounter(summoner.id, encounter.summoner_id)">
            Go
        </PrimaryButton>
    </td>


</template>

<style>

</style>
