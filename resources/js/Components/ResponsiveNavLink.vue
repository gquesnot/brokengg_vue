<script setup lang="ts">
import {computed} from 'vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? 'block w-full pl-3 pr-4 py-2 border-b-2 border-gray-200 text-left text-base font-medium text-gray-200 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'block w-full pl-3 pr-4 py-2  text-left text-base font-medium text-gray-200 hover:bg-zinc-800  focus:outline-none  focus:bg-gray-50 focus:border-zinc-800 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot/>
    </Link>
</template>
