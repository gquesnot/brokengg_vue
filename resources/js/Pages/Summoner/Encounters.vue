<script setup lang="ts">


import {SummonerEncountersPaginated} from "@/types/summoner_encounters";
import {ref, watch} from "vue";
import {debounce} from "lodash";
import {getFilters, getOnly, getParamsWithFilters, getSummoner} from "@/helpers/root_props_helpers";
import {router} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import Pagination from "@/Components/Pagination.vue";
import EncountersRow from "@/Components/Summoner/EncountersRow.vue";

const props = defineProps<{
    search?: string
    encounters: SummonerEncountersPaginated
}>();


let search = ref(props.search ?? '');
const summoner = getSummoner();

watch(search, debounce(function (value: string) {
    router.visit(route('summoner.encounters', getParamsWithFilters(getFilters(), {
        summoner: summoner.id.toString(),
        search: value
    })), {
        preserveState: true,
        preserveScroll: true,
        only: getOnly()
    })
}, 500))


</script>

<template>
    <div class="w-7/12 mx-auto my-6">

        <SummonerHeader
            tab="Encounters"
        />
        <div>
            <TextInput v-model="search" placeholder="Search Summoner"
                       class="p-2 my-3 w-25 border-1 border border-gray-200"/>
        </div>
        <VTable>
            <thead>
            <tr>
                <th class="text-left">Summoner</th>
                <th class="text-left">Count</th>
                <th class="text-left"></th>
            </tr>
            </thead>
            <tbody>
            <template v-if="encounters.data.length === 0">
                <tr>
                    <td colspan="3" class="text-center">No Encounters Found</td>
                </tr>
            </template>
            <template v-else>
                <tr v-for="(encounter, idx) in encounters.data" :key="encounter.summoner_id"
                    :class="(idx % 2 === 0 ? 'bg-gray-200' : '' ) + ' hover:bg-gray-300'">
                    <EncountersRow
                        :key="encounter.summoner_id"
                        :encounter="encounter"/>
                </tr>
            </template>

            </tbody>
        </VTable>
        <Pagination :links="encounters.links"/>

    </div>


</template>

<style>

</style>
