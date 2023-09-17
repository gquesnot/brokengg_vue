import { unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderComponent } from "vue/server-renderer";
import { Link, usePage, router } from "@inertiajs/vue3";
import { a as getParams } from "./SummonerHeader-0b1a75fc.js";
const _sfc_main = {
  __name: "Pagination",
  __ssrInlineRender: true,
  props: {
    links: { type: PaginatedLinksInterface, required: true }
  },
  setup(__props) {
    const props = __props;
    const switchPage = (url) => {
      let page = 1;
      let page_str = url.match(/page=(\d+)/);
      if (page_str) {
        page = parseInt(page_str[1]);
      }
      let filters = usePage().props.filters;
      let route_params = usePage().props.route_params;
      let only = usePage().props.only;
      let base_params = {
        page,
        ...route_params
      };
      let params = getParams(filters, base_params);
      router.visit(route(route().current(), params), {
        preserveState: true,
        only
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.links.length > 3) {
        _push(`<div${ssrRenderAttrs(_attrs)}><div class="flex flex-wrap mt-4 justify-center"><!--[-->`);
        ssrRenderList(props.links, (link, key) => {
          _push(`<!--[-->`);
          if (link.url === null) {
            _push(`<div class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded">${link.label}</div>`);
          } else {
            _push(ssrRenderComponent(unref(Link), {
              class: (link.active ? "bg-white" : "") + " mr-1 mb-1 px-4 py-3 bg-gray-300 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary",
              href: "#",
              onClick: ($event) => switchPage(link.url)
            }, null, _parent));
          }
          _push(`<!--]-->`);
        });
        _push(`<!--]--></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Pagination.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
