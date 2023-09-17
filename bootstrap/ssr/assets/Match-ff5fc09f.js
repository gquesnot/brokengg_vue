import { mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./SummonerHeader-0b1a75fc.js";
import "@inertiajs/vue3";
import "./PrimaryButton-d82933f3.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./InputError-be673cc6.js";
import "./ResponsiveNavLink-2d39c768.js";
import "vue3-datepicker";
const _sfc_main = {
  __name: "Match",
  __ssrInlineRender: true,
  props: {
    match: {
      type: Object
    }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$1, { tab: "Matches" }, null, _parent));
      _push(` Match </div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Match.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
