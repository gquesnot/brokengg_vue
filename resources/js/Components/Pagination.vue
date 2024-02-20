<script setup lang="ts">
import {Link, router} from '@inertiajs/vue3'
import {PaginationLinkInterface} from "@/types/pagination_link";
import {getRouteParams} from "@/helpers/root_props_helpers";
import {useFiltersStore, useSummonerStore} from "@/store";


const props = defineProps<{
    links: PaginationLinkInterface[]
}>();
let filtersStore = useFiltersStore()
let summonerStore = useSummonerStore()


const switchPage = (url: string | null) => {
    if (url === null) {
        return
    }
    // find page using mask
    let page = 1
    let page_str = url.match(/page=(\d+)/)
    if (page_str) {
        // @ts-ignore
        page = parseInt(page_str[1])
    }


    //@ts-ignore
    router.visit(route(route().current(), {
        ...getRouteParams(),
        ...filtersStore.toObj(),
        page: page.toString()
    }), {
        preserveState: true,
        only: summonerStore.only
    })
}

</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap mt-4 justify-center">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label"
                />

                <Link
                    v-else
                    :class="(link.active ? 'bg-gray-900' :'bg-gray-1') + ' mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary'"
                    href="#"
                    @click.prevent="switchPage(link.url)"
                    v-html="link.label"
                ></Link>
            </template>
        </div>
    </div>
</template>
