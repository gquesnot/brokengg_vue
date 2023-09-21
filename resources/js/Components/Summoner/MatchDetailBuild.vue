<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";

import {computed, ref, watch} from "vue";
import {urlItemHelper} from "@/helpers/url_helpers";
import {EventType} from "@/enums/event_type";
import moment from "moment";


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
const timestamp_to_hours_minutes_seconds = (timestamp: number): string => {
  const duration = moment.duration(timestamp - 60000, 'milliseconds');
  const hours = duration.hours();
  const minutes = duration.minutes();
  const seconds = duration.seconds();

  return [
    hours && hours.toString().padStart(2, '0'),
    minutes.toString().padStart(2, '0'),
    seconds.toString().padStart(2, '0'),
  ].filter(Boolean).join(':');
}

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
        <div class="bg-gray-1">
          Item Builds
        </div>
        <div class="flex mt-2 flex-wrap">
          <template v-for="(frame, idx)  in selected_participant.frames" :key="frame.id">
            <template v-if="frame.item_events.length > 0">
              <div class="flex mb-10 items-center" v-if="idx > first_frame_with_events">
                <div class="flex items-center h-10  border-8 border-gray-900 bg-gray-900">
                  <VIcon icon="fa fa-arrow-right" class="text-gray-4 h-5"/>
                </div>
              </div>
              <div class="flex flex-col items-center relative mb-10">
                <div class="flex items-center border-gray-900 border-4 rounded">
                  <template v-for="(item_event) in frame.item_events" :key="item_event.id">
                    <div v-if="item_event.type === EventType.ITEM_PURCHASED" class="border-gray-900 border-4">
                      <VImg :src="urlItemHelper(item_event.item.img_url)" class="w-8 h-8"/>
                    </div>
                    <div class="rounded relative border-gray-900 border-4"
                         v-else-if="item_event.type === EventType.ITEM_SOLD">
                      <VImg :src="urlItemHelper(item_event.item.img_url)" class="w-8 h-8 opacity-75"/>
                      <div class="absolute bottom-0 right-0">
                        <VIcon icon="fa fa-times" class="text-red "/>
                      </div>
                    </div>
                  </template>
                </div>
                <div class="text-center mt-1 absolute -bottom-7">
                  {{ timestamp_to_hours_minutes_seconds(frame.current_timestamp) }}
                </div>
              </div>
            </template>
          </template>
        </div>
        <div class="bg-gray-1 mt-4">
          Skill Build
        </div>
        <div class="mt-2">
          <div class="flex space-x-2">
            <template v-for="ordered_level_up_skill in ordered_level_up_skills" :key="ordered_level_up_skill">
              <div v-if="ordered_level_up_skill == 1" class="font-bold rounded px-3 py-1.5 text-blue-4 bg-zinc-700">
                Q
              </div>
              <div v-if="ordered_level_up_skill == 2" class="font-bold rounded px-3 py-1.5 text-green-400 bg-zinc-700">
                W
              </div>
              <div v-if="ordered_level_up_skill == 3" class="font-bold rounded px-3 py-1.5 text-orange-400 bg-zinc-700">
                E
              </div>
              <div v-if="ordered_level_up_skill == 4" class="font-bold rounded px-3 py-1.5 bg-indigo-500">
                R
              </div>

            </template>
          </div>
          <div class="flex mt-4 space-x-2">

            <template v-for="(frame, idx) in selected_participant.frames" :key="frame.id">
              <template v-for="(level_up_skill_event) in frame.level_up_skill_events" :key="level_up_skill_event.id">
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
                <div v-if="level_up_skill_event.skill_slot == 4" class="font-bold rounded px-3 py-1.5 bg-indigo-500">
                  R
                </div>

              </template>

            </template>
          </div>
        </div>

      </div>
      <div>

      </div>
      <div>

      </div>
    </div>

  </div>
</template>

