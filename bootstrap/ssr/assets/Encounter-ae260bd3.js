import { resolveComponent, mergeProps, unref, useSSRContext, ref, withCtx, createTextVNode, createVNode } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { _ as _sfc_main$4 } from "./SummonerHeader-0b1a75fc.js";
import moment from "moment";
import { usePage } from "@inertiajs/vue3";
import { _ as _sfc_main$3 } from "./SummonerStats-6fa23ddc.js";
import { u as urlChampionHelper } from "./url_helpers-8bee5ae0.js";
import "./PrimaryButton-d82933f3.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./InputError-be673cc6.js";
import "./ResponsiveNavLink-2d39c768.js";
import "vue3-datepicker";
const _sfc_main$2 = {
  __name: "EncounterRow",
  __ssrInlineRender: true,
  props: {
    summoner_match: {
      type: Object,
      required: true
    },
    version: {
      type: String,
      required: true
    },
    is_reverse: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  setup(__props) {
    const props = __props;
    const version = usePage().props.version;
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c;
      const _component_VImg = resolveComponent("VImg");
      if (__props.summoner_match !== null) {
        _push(`<div${ssrRenderAttrs(mergeProps({
          class: `${((_a = props.summoner_match) == null ? void 0 : _a.won) ? "bg-blue-primary" : "bg-red-primary"} flex items-center justify-start p-2 ${__props.is_reverse ? "flex-row-reverse " : ""}`
        }, _attrs))}><div>`);
        _push(ssrRenderComponent(_component_VImg, {
          src: unref(urlChampionHelper)(unref(version), (_b = __props.summoner_match.champion) == null ? void 0 : _b.img_url),
          class: "w-16 h-16"
        }, null, _parent));
        _push(`</div><div class="mx-4"><div class="flex"><div class="text-white font-bold">${ssrInterpolate(__props.summoner_match.kills.toFixed(1))}</div><div class="text-white mx-1">/</div><div class="text-black font-bold">${ssrInterpolate(__props.summoner_match.deaths.toFixed(1))}</div><div class="text-white mx-1">/</div><div class="text-white font-bold">${ssrInterpolate(__props.summoner_match.assists.toFixed(1))}</div></div><div class="font-bold text-2xl">${ssrInterpolate((_c = __props.summoner_match.kda) == null ? void 0 : _c.toFixed(2))}:1 KDA </div><div> P/Kill ${ssrInterpolate((__props.summoner_match.kill_participation * 100).toFixed(0))}% </div></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/EncounterRow.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = {
  __name: "EncounterPart",
  __ssrInlineRender: true,
  props: {
    encounter_data: {
      type: Object
    },
    is_with: {
      type: Boolean
    },
    encounter: {
      type: Object
    }
  },
  setup(__props) {
    const summoner = usePage().props.summoner;
    const has_won = (match) => {
      return match.participants.filter((participant) => {
        return participant.summoner_id === summoner.id;
      })[0].won;
    };
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.encounter_data.matches.length > 0) {
        _push(`<!--[--><div class="grid grid-cols-2"><div>`);
        _push(ssrRenderComponent(_sfc_main$3, {
          with_summoner_name: true,
          summoner_stats: __props.encounter_data.summoner_stats,
          summoner: unref(summoner)
        }, null, _parent));
        _push(`</div><div>`);
        _push(ssrRenderComponent(_sfc_main$3, {
          with_summoner_name: true,
          is_reverse: true,
          summoner_stats: __props.encounter_data.encounter_stats,
          summoner: __props.encounter,
          color: __props.is_with ? "bg-blue-500" : "bg-red-500"
        }, null, _parent));
        _push(`</div></div><div><!--[-->`);
        ssrRenderList(__props.encounter_data.matches, (match) => {
          var _a;
          _push(`<div class="grid grid-cols-3 my-4"><!--[-->`);
          ssrRenderList(match.participants, (participant) => {
            _push(`<!--[-->`);
            if (participant.summoner_id === unref(summoner).id) {
              _push(ssrRenderComponent(_sfc_main$2, {
                is_reverse: false,
                summoner_match: participant,
                version: _ctx.version,
                class: "mr-4"
              }, null, _parent));
            } else {
              _push(`<!---->`);
            }
            _push(`<!--]-->`);
          });
          _push(`<!--]--><div class="${ssrRenderClass(`${!match.participants || has_won(match) ? "bg-blue-primary" : "bg-red-primary"} flex flex-col items-center justify-center cursor-pointer`)}"><div>${ssrInterpolate((_a = match == null ? void 0 : match.queue) == null ? void 0 : _a.description)}</div><div>${ssrInterpolate(unref(moment)(match == null ? void 0 : match.match_end).fromNow())}</div><div>${ssrInterpolate(!match.participants || has_won(match) ? "Victory" : "Defeat")}</div><div>${ssrInterpolate(match == null ? void 0 : match.match_duration)}</div></div><!--[-->`);
          ssrRenderList(match.participants, (participant) => {
            _push(`<!--[-->`);
            if (participant.summoner_id === __props.encounter.id) {
              _push(ssrRenderComponent(_sfc_main$2, {
                is_reverse: true,
                summoner_match: participant,
                class: "ml-4",
                version: ""
              }, null, _parent));
            } else {
              _push(`<!---->`);
            }
            _push(`<!--]-->`);
          });
          _push(`<!--]--></div>`);
        });
        _push(`<!--]--></div><!--]-->`);
      } else {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "text-center text-2xl font-bold mt-4" }, _attrs))}> No Matches Found </div>`);
      }
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/EncounterPart.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Encounter",
  __ssrInlineRender: true,
  props: {
    encounter: {
      type: Object
    },
    vs_: {
      type: Object
    },
    with_: {
      type: Object
    }
  },
  setup(__props) {
    const summoner = usePage().props.summoner;
    const tab = ref("with");
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTabs = resolveComponent("VTabs");
      const _component_VTab = resolveComponent("VTab");
      const _component_VWindow = resolveComponent("VWindow");
      const _component_VWindowItem = resolveComponent("VWindowItem");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-10/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$4, { tab: "Encounters" }, null, _parent));
      _push(ssrRenderComponent(_component_VTabs, {
        modelValue: tab.value,
        "onUpdate:modelValue": ($event) => tab.value = $event,
        "align-tabs": "center",
        color: tab.value === "with" ? "blue" : "red"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_component_VTab, {
              value: "with",
              class: "bg-blue-500"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`WITH`);
                } else {
                  return [
                    createTextVNode("WITH")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_VTab, {
              value: "vs",
              class: "bg-red-500"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`VS`);
                } else {
                  return [
                    createTextVNode("VS")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_component_VTab, {
                value: "with",
                class: "bg-blue-500"
              }, {
                default: withCtx(() => [
                  createTextVNode("WITH")
                ]),
                _: 1
              }),
              createVNode(_component_VTab, {
                value: "vs",
                class: "bg-red-500"
              }, {
                default: withCtx(() => [
                  createTextVNode("VS")
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_component_VWindow, {
        modelValue: tab.value,
        "onUpdate:modelValue": ($event) => tab.value = $event
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_component_VWindowItem, { value: "with" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$1, {
                    encounter_data: __props.with_,
                    encounter: __props.encounter,
                    is_with: true,
                    summoner: unref(summoner)
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$1, {
                      encounter_data: __props.with_,
                      encounter: __props.encounter,
                      is_with: true,
                      summoner: unref(summoner)
                    }, null, 8, ["encounter_data", "encounter", "summoner"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_VWindowItem, { value: "vs" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$1, {
                    encounter_data: __props.vs_,
                    encounter: __props.encounter,
                    summoner: unref(summoner),
                    is_with: false
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$1, {
                      encounter_data: __props.vs_,
                      encounter: __props.encounter,
                      summoner: unref(summoner),
                      is_with: false
                    }, null, 8, ["encounter_data", "encounter", "summoner"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_component_VWindowItem, { value: "with" }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$1, {
                    encounter_data: __props.with_,
                    encounter: __props.encounter,
                    is_with: true,
                    summoner: unref(summoner)
                  }, null, 8, ["encounter_data", "encounter", "summoner"])
                ]),
                _: 1
              }),
              createVNode(_component_VWindowItem, { value: "vs" }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$1, {
                    encounter_data: __props.vs_,
                    encounter: __props.encounter,
                    summoner: unref(summoner),
                    is_with: false
                  }, null, 8, ["encounter_data", "encounter", "summoner"])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Encounter.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
