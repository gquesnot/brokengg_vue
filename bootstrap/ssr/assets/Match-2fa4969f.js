import { defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./SummonerHeader-7f8813f6.js";
import { _ as _sfc_main$2 } from "./MatchesRow-40e03afd.js";
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
  __name: "Match",
  __ssrInlineRender: true,
  props: {
    summoner_match: {},
    summoner_encounter_count: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$1, { tab: "Matches" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$2, {
        auto_open: true,
        summoner_match: _ctx.summoner_match,
        summoner_encounter_count: _ctx.summoner_encounter_count
      }, null, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Match.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
