<script setup lang="ts">


import PrimaryButton from "@/Components/PrimaryButton.vue";
import {EncounterInterface} from "@/Types/summoner_encounters";
import {navigateToEncounter} from "@/Helpers/router_helpers";
import {getSummoner} from "@/Helpers/root_props_helpers";



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
        {{ encounter.encounter_count }}
    </td>
    <td>
        <PrimaryButton @click="navigateToEncounter(summoner.id, encounter.summoner_id)">
            Go
        </PrimaryButton>
    </td>


</template>

<style>

</style>
