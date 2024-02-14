import { defineComponent, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderComponent } from "vue/server-renderer";
import { Link, router } from "@inertiajs/vue3";
import { f as getRouteParams, b as getParamsWithFilters, c as getFilters, d as getOnly } from "./SummonerHeader-7f8813f6.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Pagination",
  __ssrInlineRender: true,
  props: {
    links: {}
  },
  setup(__props) {
    const switchPage = (url) => {
      if (url === null) {
        return;
      }
      let page = 1;
      let page_str = url.match(/page=(\d+)/);
      if (page_str) {
        page = parseInt(page_str[1]);
      }
      const route_params = getRouteParams();
      router.visit(route(route().current(), getParamsWithFilters(getFilters(), {
        ...route_params,
        page: page.toString()
      })), {
        preserveState: true,
        only: getOnly()
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      if (_ctx.links.length > 3) {
        _push(`<div${ssrRenderAttrs(_attrs)}><div class="flex flex-wrap mt-4 justify-center"><!--[-->`);
        ssrRenderList(_ctx.links, (link, key) => {
          _push(`<!--[-->`);
          if (link.url === null) {
            _push(`<div class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded">${link.label}</div>`);
          } else {
            _push(ssrRenderComponent(unref(Link), {
              class: (link.active ? "bg-gray-900" : "bg-gray-1") + " mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary",
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
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Pagination.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
