<script setup lang="ts">
import {Link, router} from '@inertiajs/vue3'
import {PaginationLinkInterface} from "@/types/pagination_link";
import {getFilters, getOnly, getParamsWithFilters, getRouteParams} from "@/helpers/root_props_helpers";


const props = defineProps<{
    links: PaginationLinkInterface[]
}>();


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
    const route_params = getRouteParams()

    //@ts-ignore
    router.visit(route(route().current(), getParamsWithFilters(getFilters(), {
        ...route_params,
        page: page.toString()
    })), {
        preserveState: true,
        only: getOnly()
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
                    :class="(link.active ? 'bg-white' :'') + ' mr-1 mb-1 px-4 py-3 bg-gray-300 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary'"
                    href="#"
                    @click.prevent="switchPage(link.url)"
                    v-html="link.label"
                ></Link>
            </template>
        </div>
    </div>
</template>
