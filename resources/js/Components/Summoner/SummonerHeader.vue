<script setup lang="ts">


import {onMounted, Ref, ref} from "vue";
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import Datepicker from "vue3-datepicker";

import {FiltersInterface} from "@/types/filters";
import {
    getChampionOptions,
    getFilters,
    getOnly,
    getParamsWithFilters,
    getQueueOptions,
    getRouteParams,
    getSummoner
} from "@/helpers/root_props_helpers";
import {urlProfilIconHelper} from "@/helpers/url_helpers";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const props = defineProps<{
    tab: string
}>();

const filters = getFilters();
const summoner = getSummoner();
const champion_options = getChampionOptions();
const queue_options = getQueueOptions();

onMounted(() => {
  //@ts-ignore
    window.Echo.channel('summoner.'+summoner.id).listen('SummonerUpdated', (e: any) => {
      e.preventDefault()
        router.reload({
            preserveState: true,
            preserveScroll: true,
            only: getOnly()
        })
    })
})


const form = useForm<{
    filters: {
        queue_id?: number
        champion_id?: number
        start_date?: Date
        end_date?: Date
        should_filter_encounters?: boolean
    }
}>({
    filters: {
        queue_id: filters.queue_id,
        champion_id: filters.champion_id,
        start_date: filters.start_date ? new Date(filters.start_date) : undefined,
        end_date: filters.end_date ? new Date(filters.end_date) : undefined,
        should_filter_encounters: filters.should_filter_encounters,
    }
});


export interface TabInterface {
    label: string
    route: string
}

const tabs: Ref<TabInterface[]> = ref([
    {label: 'Matches', route: 'summoner.matches'},
    {label: 'Champions', route: 'summoner.champions'},
    {label: 'Encounters', route: 'summoner.encounters'},
    {label: 'LiveGame', route: 'summoner.live-game'},
])


const updateSummoner = () => {
    router.patch(route('summoner.update', {summoner: summoner.id}))
}

const applyFilter = () => {
    let new_filters = formToFilters()
    //@ts-ignore
    usePage().props.filters = new_filters

    //@ts-ignore
    router.visit(route(route().current(), getParamsWithFilters(new_filters, getRouteParams())), {
        preserveState: true,
        only: getOnly()
    })

}

const getTab = (label: string): TabInterface | undefined => {
    return tabs.value.find((tab: TabInterface) => tab.label === label)
}


const clearFilter = () => {
    form.filters.queue_id = undefined
    form.filters.champion_id = undefined
    form.filters.start_date = undefined
    form.filters.end_date = undefined
    form.filters.should_filter_encounters = false
    // @ts-ignore
    usePage().props.filters = formToFilters()

    //@ts-ignore
    router.visit(route(route().current(), getRouteParams()), {
        preserveState: true,
        only: getOnly()
    })
}

const formToFilters = () => {
    let filters: FiltersInterface = {
        queue_id: form.filters.queue_id,
        champion_id: form.filters.champion_id,
        start_date: form.filters.start_date ? form.filters.start_date.toISOString() : undefined,
        end_date: form.filters.end_date ? form.filters.end_date.toISOString() : undefined,
        should_filter_encounters: form.filters.should_filter_encounters ? form.filters.should_filter_encounters : false,
    }
    if (filters.start_date !== undefined) {
        filters.start_date = filters.start_date.split('T')[0]
    }
    if (filters.end_date !== undefined) {
        filters.end_date = filters.end_date.split('T')[0]
    }
    return filters
}


const switchTab = (label: string) => {
    let tab = getTab(label)
    if (tab) {
        router.visit(route(tab.route, {
            summoner: summoner.id,
        }), {
            preserveState: true,
        })
    }
}


</script>

<template>
    <Head :title="summoner.name"/>
    <div class="flex justify-center">
        <a :href="route('home')">
            <div class="text-3xl font-bold mb-8"> BROKEN.GG</div>
        </a>
    </div>
    <div class="">
        <div class="flex justify-between">
            <div class="flex mt-4 ">
                <div class="w-16">
                    <VImg :width="75" :height="75"
                          :src="urlProfilIconHelper(summoner.profile_icon_id)"/>

                </div>
                <div class="ml-4">
                    <div>
                        {{ summoner.name }}
                    </div>
                    <PrimaryButton @click="updateSummoner">
                        Update
                    </PrimaryButton>
                </div>
            </div>
            <div class="bg-gray-700 w-1/2 p-4 text-gray-200 rounded">
                <div class="flex">
                    <div class="w-1/2">
                        <div>
                            <VAutocomplete
                                v-model="form.filters.queue_id"
                                :items="queue_options"
                                item-value="value"
                                item-title="label"
                                label="Queue"
                                id="queue"
                                density="comfortable"
                                name="queue"
                                :clearable="true"
                            />
                        </div>
                        <div>
                            <VAutocomplete
                                v-model="form.filters.champion_id"
                                :items="champion_options"
                                item-value="value"
                                item-title="label"
                                label="Champion"
                                density="comfortable"
                                id="champion_id"
                                name="champion_id"
                                :clearable="true"

                            />
                        </div>
                        <div>
                            <v-switch label="Filter Encounters"
                                      v-model="form.filters.should_filter_encounters"></v-switch>
                        </div>
                    </div>
                    <div class="w-1/2 ml-4">
                        <div>
                            <label for="start_time">Start Time</label>
                            <Datepicker
                                v-model="form.filters.start_date"
                                id="start_time"
                                name="start_time"
                                class="text-black"
                                clearable
                                :format="['YYYY-MM-DD']"
                                :input-props="{placeholder: 'YYYY-MM-DD'}"
                            />
                        </div>
                        <div>
                            <label for="end_time">End Time</label>
                            <Datepicker
                                v-model="form.filters.end_date"
                                id="end_time"
                                name="end_time"
                                class="text-black"
                                clearable
                                :format="['YYYY-MM-DD']"
                                :input-props="{placeholder: 'YYYY-MM-DD'}"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex ">
                    <PrimaryButton @click="applyFilter" class="ml-4">
                        Apply
                    </PrimaryButton>
                    <PrimaryButton @click="clearFilter" class="ml-4">
                        Clear
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-6 w-1/2 mr-auto mb-4">
        <div v-for="tab in tabs">
            <ResponsiveNavLink class="flex items-center justify-center" @click.prevent="switchTab(tab.label)" href="#"
                               :active="tab.label == props.tab">
                {{ tab.label }}
            </ResponsiveNavLink>
        </div>
    </div>
</template>

<style>

</style>
