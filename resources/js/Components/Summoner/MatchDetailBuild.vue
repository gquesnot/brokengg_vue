<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";

import {computed, ref, watch} from "vue";
import MatchDetailItemsBuild from "@/Components/Summoner/MatchDetailItemsBuild.vue";
import MatchDetailSkillBuild from "@/Components/Summoner/MatchDetailSkillBuild.vue";
import MatchDetailRuneBuild from "@/Components/Summoner/MatchDetailRuneBuild.vue";


const props = defineProps<{
    summoner_match: SummonerMatchInterface,
    match_detail: SummonerMatchInterface[],
}>();

const selected_participant_id = ref<number | null>(null);
const selected_participant = ref<SummonerMatchInterface | null>(null);
const ordered_level_up_skills = ref<number[]>([]);
const first_frame_with_events = ref<number>(-1);

const participant_options = computed(() =>
    props.summoner_match.match.participants.map(({id, summoner, champion}) => ({
        value: id,
        label: `${summoner.name} - ${champion.name}`,
    }))
);

watch(selected_participant_id, (value) => {
    if (!props.match_detail) return;

    const participant = props.match_detail.find(detail => detail.id === value);
    if (!participant) return;

    selected_participant.value = participant;
    ordered_level_up_skills.value = [];
    first_frame_with_events.value = -1;

    const count_skills: Record<number, number> = {};

    participant.frames.forEach((frame, idx) => {
        if (first_frame_with_events.value === -1 && frame.item_events.length) {
            first_frame_with_events.value = idx;
        }

        frame.level_up_skill_events.forEach(({skill_slot}) => {
            count_skills[skill_slot] = (count_skills[skill_slot] || 0) + 1;

            if (count_skills[skill_slot] === 5) {
                ordered_level_up_skills.value.push(skill_slot);
                delete count_skills[skill_slot];
            }
        });
    });

    const skillSlots = Object.keys(count_skills)
        .map(Number)
        .filter(skillSlot => skillSlot !== 4)  // Filter out skill 4
        .sort((a, b) => count_skills[b] - count_skills[a]);
    ordered_level_up_skills.value.push(...skillSlots);
});

selected_participant_id.value = props.summoner_match.id


</script>

<template>
    <div class="my-1 bg-gray-1 p-4 rounded text-gray-200  w-full">
        <div>
            <VAutocomplete
                v-model="selected_participant_id"
                :items="participant_options"
                label="Select a participant"
                item-value="value"
                item-title="label"
                density="comfortable"
            />
        </div>
        <div v-if="selected_participant">
            <div>
                <MatchDetailItemsBuild :selected_participant="selected_participant" :first_frame_with_events="first_frame_with_events"/>
                <MatchDetailSkillBuild :selected_participant="selected_participant" :ordered_level_up_skills="ordered_level_up_skills"/>
                <MatchDetailRuneBuild :selected_participant="selected_participant"/>
            </div>
        </div>

    </div>
</template>

