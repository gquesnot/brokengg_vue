<script setup lang="ts">
import {computed, Ref, ref, watch, watchEffect} from 'vue';
import {usePage} from "@inertiajs/vue3";


const is_open = ref(false);
let alert_message = ref<string | undefined>(undefined);

// check evry 0.5s if there is an error in the props
let interval = setInterval(() => {
  if (usePage().props.errors.api) {
    alert_message.value = usePage().props.errors.api;
    is_open.value = true;
    usePage().props.errors = {};
  }
}, 500)


</script>

<template>
  <div class="fixed right-10 bottom-10 z-20">
    <v-alert
        v-model="is_open"
        border="start"
        type="error"
        :text="alert_message"
        closable
    >
    </v-alert>
  </div>
</template>
