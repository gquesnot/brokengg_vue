<script setup lang="ts">


import PrimaryButton from "@/Components/PrimaryButton.vue";
import {EncounterInterface} from "@/types/summoner_encounters";
import {navigateToEncounter} from "@/helpers/router_helpers";
import {urlProPlayerHelper} from "@/helpers/url_helpers";
import {withoutTagLine} from "@/helpers/summoner_name_helper";
import {useSummonerStore} from "@/store";


const props = defineProps<{
    encounter: EncounterInterface
}>();

let summonerStore = useSummonerStore();

</script>

<template>
    <td>
        {{ withoutTagLine(encounter.name) }}
    </td>
    <td>
        <div class="flex items-center">
            <div>
                {{ encounter.encounter_count }}
            </div>
            <div class="flex justify-center text-xs ml-2" v-if="encounter.summoner.pro_player">
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
        <PrimaryButton @click="navigateToEncounter(summonerStore.summoner.id, encounter.summoner_id)">
            Go
        </PrimaryButton>
    </td>


</template>

<style>

</style>
