<script setup lang="ts">

import {SummonerMatchInterface} from "@/types/summoner_match";
import {urlItemHelper, urlPerkHelper} from "@/helpers/url_helpers";
const props = defineProps<{
    selected_participant: SummonerMatchInterface,
}>();



</script>

<template>
    <div class="bg-gray-1 mt-4">
        Runes
    </div>
    <div class="mt-2 flex w-1/2 ">
      <div class="w-1/6 flex flex-col justify-end space-y-2 items-center">
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.primary_style.img_url)" class="w-10 h-10 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center mt-2">
          <VImg :src="urlPerkHelper(selected_participant.perks.primary_selection.img_url)"
                class="w-10 h-10 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.primary_selection1.img_url)"
                class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.primary_selection2.img_url)"
                class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.primary_selection3.img_url)"
                class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="text-center">
              {{ selected_participant.perks.primary_style.name }}
        </div>
      </div>
      <div class="w-1/4 flex flex-col justify-end space-y-2 items-center">
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.sub_style.img_url)" class="w-10 h-10 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center mt-2">
          <VImg :src="urlPerkHelper(selected_participant.perks.sub_selection1.img_url)"
                class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.sub_selection2.img_url)"
                class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="text-center">
              {{ selected_participant.perks.sub_style.name }}
        </div>
      </div>
      <div class="w-1/4 flex flex-col justify-end space-y-2 items-center">
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.offense.img_url)" class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.flex.img_url)" class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="flex items-center">
          <VImg :src="urlPerkHelper(selected_participant.perks.defense.img_url)" class="w-8 h-8 bg-gray-950 rounded-full"/>
        </div>
        <div class="text-center">
              Rune Stats
        </div>
      </div>

    </div>
</template>

