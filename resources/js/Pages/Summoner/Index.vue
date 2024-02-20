<script setup lang="ts">
import {Head, useForm} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AlertApi from "@/Components/AlertApi.vue";

const props = defineProps<{}>();

const form = useForm({
    summoner_name: 'random iron#EUW',
});


const searchSummoner = () => {
    form.post(route('summoner.store'), {
        preserveScroll: true,
        onError() {
        }
    });
};


</script>

<template>
    <Head title="Accueil"/>
    <div class="flex justify-center items-center min-h-screen bg-dots-darker bg-center">
        <v-card class="w-1/2 p-8">

            <div class="bg-gray-1">
                <v-card-title class="text-center"><span class="text-4xl">BROKEN.GG</span></v-card-title>
                <v-card-text class=" flex flex-col items-center justify-center">
                    <InputLabel for="summoner_name" value="Search Summoner" class="mb-2"/>
                    <div class="flex mx-auto items-center w-fit">
                        <div>
                            <TextInput id="summoner_name" ref="inputTextSummonerName" v-model="form.summoner_name"
                                       class="p-2 border"/>
                            <InputError :message="form.errors.summoner_name" class="mt-2"/>
                        </div>
                    </div>

                </v-card-text>
                <v-card-actions class="flex justify-center">
                    <PrimaryButton
                        class="mt-4 text-2xl"
                        @click="searchSummoner">
                        Search
                    </PrimaryButton>
                </v-card-actions>

            </div>

        </v-card>
    </div>

    <AlertApi/>

</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
