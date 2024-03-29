<script setup lang="ts">
import SummonerHeader from "@/Components/Summoner/SummonerHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import LiveGameRowPart from "@/Components/Summoner/LiveGameRowPart.vue";
import {useFiltersStore, useSummonerStore} from "@/store";
import {BeforeFiltersInterface} from "@/types/filters";
import {SummonerInterface} from "@/types/summoner";
import {OptionInterface} from "@/types/option";


const props = defineProps<{
    live_game: any | null
    fake_live_game: any | null,
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


const form = useForm({
    lobby_search: 'Random Iron #EUW joined the lobby\n' +
        'Nos Unforgiven #EUW joined the lobby\n'
})


const searchLobby = () => {
    router.visit(route('summoner.live-game', {summoner_id: summonerStore.summoner.id, lobby_search: form.lobby_search}),
        {
            preserveState: true,
            onError: (error) => {
            }
        })
}


</script>

<template>
    <div class="w-7/12 mx-auto my-6">

        <SummonerHeader
            tab="LiveGame"
        />
        <div v-if="live_game !== null">
            <div class="">
                <div class="flex">
                    <div>{{ live_game['queue']['description'] }}</div>
                    <div class="ml-2">{{ live_game['map']['description'] }}</div>
                    <div class="ml-2">{{ live_game['duration'] }}</div>
                </div>
                <div class="text-xl font-bold text-blue-3">
                    Blue Team
                </div>
                <div v-for="participant in live_game['participants']" :key="participant['summoner']['id']">
                    <LiveGameRowPart v-if="participant['teamId'] == 100" :is_my_team="true"
                                     :participant="participant"/>
                </div>
                <div class="text-xl font-bold text-red-3 mt-4">
                    Red Team
                </div>
                <div v-for="participant in live_game['participants']" :key="participant['summoner']['id']">
                    <LiveGameRowPart v-if="participant['teamId'] == 200" :is_my_team="false"
                                     :key="participant['summoner']['id']" :participant="participant"/>
                </div>
            </div>
        </div>
        <div v-else class="flex flex-col w-full justify-center items ">

            <div class="flex flex-col justify-center items-center min-h-[10rem] ">
                <div>Summoner is not in a live game</div>
                <VTextarea v-model="form.lobby_search" class="my-2 w-1/2 mx-auto" placeholder="Lobby Summoner"
                           rows="5"/>
                <div class="flex">
                    <PrimaryButton @click="searchLobby">Search</PrimaryButton>
                    <PrimaryButton @click="router.reload({preserveState:true, only:['live_game', 'errors']})"
                                   class="ml-4">
                        Refresh
                    </PrimaryButton>
                </div>
            </div>
            <div v-if="fake_live_game" class="flex items-center   flex-col">
                <LiveGameRowPart :key="participant['summoner']['id']"
                                 v-for="participant in fake_live_game['participants']"
                                 :is_my_team="true" :participant="participant"/>
            </div>

        </div>
    </div>
</template>

<style>

</style>
