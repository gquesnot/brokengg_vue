<script setup lang="ts">


import {SummonerEncountersPaginated} from "@/types/summoner_encounters";
import {ref, watch} from "vue";
import {debounce} from "lodash";
import {router} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import Pagination from "@/Components/Pagination.vue";
import EncountersRow from "@/Components/Summoner/EncountersRow.vue";
import {useFiltersStore, useSummonerStore} from "@/store";
import {SummonerInterface} from "@/types/summoner";
import {BeforeFiltersInterface} from "@/types/filters";
import {OptionInterface} from "@/types/option";

const props = defineProps<{
    search?: string
    encounters: SummonerEncountersPaginated,
    summoner: SummonerInterface,
    filters: BeforeFiltersInterface,
    version: string,
    champion_options: OptionInterface[],
    queue_options: OptionInterface[],
    only: string[],
}>();

const summonerStore = useSummonerStore();
const filtersStore = useFiltersStore();
summonerStore.setStore(props.summoner, props.version, props.champion_options, props.queue_options, props.only);
filtersStore.setStore(props.filters);

let search = ref(props.search ?? '');


watch(search, debounce(function (value: string) {
    router.visit(route('summoner.encounters', {
        summoner_id: summonerStore.summoner.id,
        search: value,
        ...filtersStore.toObj()
    }), {
        preserveState: true,
        preserveScroll: true,
    })
}, 500))


</script>

<template>
    <div class="w-7/12 mx-auto my-6 text-gray-5">

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
                    :class="(idx % 2 === 0 ? 'bg-zinc-800' : '' ) + ' hover:bg-zinc-900'">
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
