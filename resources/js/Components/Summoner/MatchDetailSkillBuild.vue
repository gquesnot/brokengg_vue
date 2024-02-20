<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";


const props = defineProps<{
    ordered_level_up_skills: number[],
    selected_participant: SummonerMatchInterface
}>();


</script>

<template>
    <div class="bg-gray-1 mt-4">
        Skill Build
    </div>
    <div class="mt-2">
        <div class="flex space-x-2">
            <template v-for="ordered_level_up_skill in ordered_level_up_skills"
                      :key="ordered_level_up_skill">
                <div v-if="ordered_level_up_skill == 1"
                     class="font-bold rounded px-3 py-1.5 text-blue-4 bg-zinc-700">
                    Q
                </div>
                <div v-if="ordered_level_up_skill == 2"
                     class="font-bold rounded px-3 py-1.5 text-green-400 bg-zinc-700">
                    W
                </div>
                <div v-if="ordered_level_up_skill == 3"
                     class="font-bold rounded px-3 py-1.5 text-orange-400 bg-zinc-700">
                    E
                </div>
                <div v-if="ordered_level_up_skill == 4" class="font-bold rounded px-3 py-1.5 bg-indigo-500">
                    R
                </div>

            </template>
        </div>
        <div class="flex mt-4 space-x-2">

            <template v-for="(frame, idx) in selected_participant.frames" :key="frame.id">
                <template v-for="(level_up_skill_event) in frame.level_up_skill_events"
                          :key="level_up_skill_event.id">
                    <div v-if="level_up_skill_event.skill_slot == 1"
                         class="font-bold rounded px-3 py-1.5 text-blue-4 bg-zinc-700">
                        Q
                    </div>
                    <div v-if="level_up_skill_event.skill_slot == 2"
                         class="font-bold rounded px-3 py-1.5 text-green-400 bg-zinc-700">
                        W
                    </div>
                    <div v-if="level_up_skill_event.skill_slot == 3"
                         class="font-bold rounded px-3 py-1.5 text-orange-400 bg-zinc-700">
                        E
                    </div>
                    <div v-if="level_up_skill_event.skill_slot == 4"
                         class="font-bold rounded px-3 py-1.5 bg-indigo-500">
                        R
                    </div>

                </template>

            </template>
        </div>
    </div>
</template>

