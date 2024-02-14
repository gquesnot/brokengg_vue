import { defineComponent, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import { g as getSummoner, _ as _sfc_main$1 } from "./SummonerHeader-7f8813f6.js";
import { _ as _sfc_main$4 } from "./Pagination-ff514cc5.js";
import { _ as _sfc_main$2 } from "./SummonerStats-98390fac.js";
import { _ as _sfc_main$3 } from "./MatchesRow-40e03afd.js";
import "@inertiajs/vue3";
import "vue3-datepicker";
import "./PrimaryButton-cbcf38ef.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./ResponsiveNavLink-d4a8b54d.js";
import "moment";
import "./AlertApi-748a9f42.js";
import "vue-chartjs";
import "axios";
import "./router_helpers-0edbd391.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Matches",
  __ssrInlineRender: true,
  props: {
    matches: {},
    summoner_stats: {},
    summoner_encounter_count: {}
  },
  setup(__props) {
    const summoner = getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$1, { tab: "Matches" }, null, _parent));
      if (_ctx.summoner_stats) {
        _push(ssrRenderComponent(_sfc_main$2, {
          summoner_stats: _ctx.summoner_stats,
          summoner: unref(summoner),
          justify: "start"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<!--[-->`);
      ssrRenderList(_ctx.matches.data, (match) => {
        _push(`<div class="flex flex-col">`);
        _push(ssrRenderComponent(_sfc_main$3, {
          key: match.id,
          summoner_match: match,
          summoner_encounter_count: _ctx.summoner_encounter_count
        }, null, _parent));
        _push(`</div>`);
      });
      _push(`<!--]-->`);
      _push(ssrRenderComponent(_sfc_main$4, {
        links: _ctx.matches.links
      }, null, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Matches.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
