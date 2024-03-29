<script setup lang="ts">
import {urlChampionHelper, urlProPlayerHelper} from "@/helpers/url_helpers";
import {SummonerMatchInterface} from "@/types/summoner_match";
import {navigateToEncounter, navigateToSummoner} from "@/helpers/router_helpers";
import {SummonerEncounterCountInterface} from "@/types/summoner_encounter_count";
import {withoutTagLine} from "@/helpers/summoner_name_helper";
import {useSummonerStore} from "@/store";

const props = defineProps<{
    participant: SummonerMatchInterface
    summoner_encounter_count: SummonerEncounterCountInterface
    is_self: boolean
}>();

let summonerStore = useSummonerStore();

</script>

<template>
    <div class="flex items-center justify-center h-5 mb-1 min-w-[12rem]">
        <div>
            <VImg
                :alt="participant.champion?.name ?? ''"
                :src="urlChampionHelper( participant.champion?.img_url)"
                :class="'w-5 h-5 '+ (is_self ? 'rounded-full' : 'rounded')"/>

        </div>

        <div class="flex ml-1 w-8 justify-center text-sm">
            <template v-if="is_self">
                <div class="w-3 h-3 flex items-center justify-center">
                    <VIcon icon="fa fa-user-o" class="w-3 h-3"/>
                </div>
            </template>
            <template v-else>
                <a href="#"
                   @click.prevent="navigateToEncounter(summonerStore.summoner.id, participant.summoner_id)">

                    <template
                        v-if="summoner_encounter_count.hasOwnProperty(participant.summoner_id)">
                        {{ summoner_encounter_count[participant.summoner_id] }}
                    </template>
                    <template v-else>
                        1
                    </template>
                </a>

            </template>
        </div>
        <div class="flex ml-1 justify-center text-xs" v-if="participant.summoner.pro_player">
            <a :href="urlProPlayerHelper(participant.summoner.pro_player.slug)">
                <div class="bg-purple-800 py-0.5 px-1 rounded">
                    PRO
                    <v-tooltip
                        activator="parent"
                        location="bottom"
                        class="text-center"
                    >
                        <p>
                            {{ participant.summoner.pro_player?.team_name }}
                        </p>
                        <p>
                            {{ participant.summoner.pro_player?.name }}
                        </p>
                    </v-tooltip>
                </div>
            </a>
        </div>
        <div class="ml-1 truncate w-64 cursor-pointer " @click="navigateToSummoner(participant.summoner_id)">
            {{ withoutTagLine(participant.summoner.name) }}
        </div>
    </div>
</template>
