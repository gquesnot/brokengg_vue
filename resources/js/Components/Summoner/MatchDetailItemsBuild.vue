<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";

import {urlItemHelper} from "@/helpers/url_helpers";
import {EventType} from "@/enums/event_type";
import moment from "moment";


const props = defineProps<{
    selected_participant: SummonerMatchInterface,
    first_frame_with_events: number,
}>();


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


</script>

<template>
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
                        <template v-for="(item_event) in frame.item_events"
                                  :key="`${item_event.item_id}-${item_event.type}-${item_event.summoner_match_frame_id}`">
                            <div v-if="item_event.type === EventType.ITEM_PURCHASED"
                                 class="relative border-gray-900 border-4">
                                <VImg :src="urlItemHelper(item_event.item.img_url)" class="w-8 h-8"/>
                                <div v-if="item_event.item_count > 1"
                                     class="text-center flex justify-center items-center  text-sm absolute bottom-0 right-0 bg-gray-800 font-bold rounded w-[17px] h-[17px] border-4 border-gray-800">
                                    <span>{{ item_event.item_count }}</span>
                                </div>
                            </div>
                            <div class="relative rounded  border-gray-900 border-4"
                                 v-else-if="item_event.type === EventType.ITEM_SOLD">
                                <VImg :src="urlItemHelper(item_event.item.img_url)"
                                      class="w-8 h-8 opacity-75"/>
                                <div class="absolute -bottom-0.5 -right-0.5">
                                    <VIcon icon="fa fa-times" class="text-red "/>
                                </div>
                                <div v-if="item_event.item_count > 1"
                                     class="text-center flex justify-center items-center  text-sm absolute bottom-0 right-0 bg-gray-800 font-bold rounded w-[17px] h-[17px] border-4 border-gray-800">
                                    <span>{{ item_event.item_count }}</span>
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
</template>

