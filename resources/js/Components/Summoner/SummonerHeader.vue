<script setup lang="ts">

import {onMounted, Ref, ref} from "vue";
import {Head, router, useForm} from "@inertiajs/vue3";
import Datepicker from "vue3-datepicker";
import {getRouteParams} from "@/helpers/root_props_helpers";
import {urlProfilIconHelper} from "@/helpers/url_helpers";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import {navigateToSummoner} from "@/helpers/router_helpers";
import AlertApi from "@/Components/AlertApi.vue";
import {tagLineOnly, withoutTagLine} from "@/helpers/summoner_name_helper";
import {useFiltersStore, useSummonerStore} from "@/store";
import moment from "moment";
import _ from 'lodash';

const props = defineProps<{
    tab: string
}>();

const filtersStore = useFiltersStore();
const summonerStore = useSummonerStore();


const updateSummoner = () => {
    router.patch(route('summoner.update', {summoner: summonerStore.summoner.id}))
}

//FILTERS
let form = useForm(
    {
        queue_id: filtersStore.queue_id,
        champion_id: filtersStore.champion_id,
        start_date: filtersStore.start_date ? new Date(filtersStore.start_date) : undefined,
        end_date: filtersStore.end_date ? new Date(filtersStore.end_date) : undefined,
        should_filter_encounters: filtersStore.should_filter_encounters,
    }
)

const applyFilter = () => {
    updateStoreFromForm();
    //@ts-ignore
    router.visit(route(route().current(), {
        ...getRouteParams(),
        ...filtersStore.toObj()
    }), {
        preserveState: true,
        preserveScroll: true,
        only: summonerStore.only
    })

}

const clearFilter = () => {
    form.queue_id = undefined
    form.champion_id = undefined
    form.start_date = undefined
    form.end_date = undefined
    form.should_filter_encounters = false
    updateStoreFromForm()
    //@ts-ignore
    router.visit(route(route().current(), getRouteParams()), {
        preserveState: true,
        preserveScroll: true,
        only: summonerStore.only
    })
}

const updateStoreFromForm = () => {
    if (form.start_date !== undefined) {
        filtersStore.start_date = moment(form.start_date.toISOString().split('T')[0]).add(1, 'days').format('YYYY-MM-DD')
    }
    if (form.end_date !== undefined) {
        filtersStore.end_date = moment(form.end_date.toISOString().split('T')[0]).add(1, 'days').format('YYYY-MM-DD')
    }
    filtersStore.should_filter_encounters = form.should_filter_encounters
    filtersStore.queue_id = form.queue_id
    filtersStore.champion_id = form.champion_id
}

// TABS
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


const getTab = (label: string): TabInterface | undefined => {
    return tabs.value.find((tab: TabInterface) => tab.label === label)
}

const switchTab = (label: string) => {
    let tab = getTab(label)
    if (tab) {
        router.visit(route(tab.route, {
            summoner_id: summonerStore.summoner.id,
            ...filtersStore.toObj()
        }), {
            preserveState: true,
            preserveScroll: true,
        })
    }
}

// EVENTS
onMounted(() => {
    const debouncedRefresh = _.debounce(() => {
        //@ts-ignore
        router.visit(route(route().current(), {
            ...getRouteParams(),
            ...filtersStore.toObj()
        }), {
            preserveScroll: true,
        });
    }, 3000);
    window.Echo.channel('summoner.' + summonerStore.summoner.id)
        .listen('.summoner.updated', (e: any) => {
            console.log(`summoner.updated on ${summonerStore.summoner.id}`, e)
            debouncedRefresh();
        });
})


</script>

<template>
    <Head :title="summonerStore.summoner.name"/>
    <div class="flex justify-center">
        <a :href="route('home')">
            <div class="text-3xl font-bold mb-8"> BROKEN.GG</div>
        </a>
    </div>
    <div class="">
        <div class="flex justify-between">
            <div class="flex mt-4 ">
                <div class="w-20">
                    <VImg :width="150" :height="80"
                          :src="urlProfilIconHelper(summonerStore.summoner.profile_icon_id)"/>
                </div>
                <div class="ml-4 flex flex-col font-bold text-xl">
                    <div @click="navigateToSummoner(summonerStore.summoner.id)" class="cursor-pointer">
                        {{ withoutTagLine(summonerStore.summoner.name) }} <span
                        class="text-gray-400">#{{ tagLineOnly(summonerStore.summoner.name) }}</span>
                    </div>
                    <div class="mb-1 text-base">
                        <template v-if="summonerStore.summoner.solo_q">
                            {{ summonerStore.summoner?.solo_q?.tier }} {{ summonerStore.summoner.solo_q?.rank }}
                        </template>
                        <template v-else>
                            lvl {{ summonerStore?.summoner?.summoner_level }}
                        </template>
                    </div>

                    <PrimaryButton @click="updateSummoner" class="justify-center">
                        Update
                    </PrimaryButton>
                </div>
            </div>
            <div class="bg-gray-1 w-1/2 p-4 text-gray-200 rounded">
                <div class="flex">
                    <div class="w-1/2">
                        <div>
                            <VAutocomplete
                                v-model="form.queue_id"
                                :items="summonerStore.queue_options"
                                item-value="value"
                                item-title="label"
                                label="Queue"
                                id="queue"
                                density="comfortable"
                                name="queue"
                                class="mt-4"
                                :clearable="true"
                            />
                        </div>
                        <div>
                            <VAutocomplete
                                v-model="form.champion_id"
                                :items="summonerStore.champion_options"
                                item-value="value"
                                item-title="label"
                                label="Champion"
                                density="comfortable"
                                id="champion_id"
                                name="champion_id"
                                class=""
                                :clearable="true"

                            />
                        </div>
                        <div>
                            <v-switch label="Filter Encounters"
                                      v-model="form.should_filter_encounters"></v-switch>
                        </div>
                    </div>
                    <div class="w-1/2 ml-4">
                        <div>
                            <label for="start_time">Start Date</label>
                            <Datepicker
                                v-model="form.start_date"
                                id="start_time"
                                name="start_time"
                                class="text-gray-5"
                                clearable
                                :format="['YYYY-MM-DD']"
                                :input-props="{placeholder: 'YYYY-MM-DD'}"
                            />
                        </div>
                        <div class="mt-1.5">
                            <label for="end_time">End Date</label>
                            <Datepicker
                                v-model="form.end_date"
                                id="end_time"
                                name="end_time"
                                class="text-gray-5"
                                clearable
                                :format="['YYYY-MM-DD']"
                                :input-props="{placeholder: 'YYYY-MM-DD'}"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex ">
                    <PrimaryButton @click="applyFilter">
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
    <AlertApi/>
</template>

<style>

</style>
