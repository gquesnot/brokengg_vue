import { defineComponent, resolveComponent, mergeProps, unref, useSSRContext, ref, withCtx, createTextVNode, createVNode } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { u as urlChampionHelper, g as getSummoner, _ as _sfc_main$4 } from "./SummonerHeader-7f8813f6.js";
import moment from "moment";
import { _ as _sfc_main$3 } from "./SummonerStats-98390fac.js";
import "@inertiajs/vue3";
import "vue3-datepicker";
import "./PrimaryButton-cbcf38ef.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
import "./ResponsiveNavLink-d4a8b54d.js";
import "./AlertApi-748a9f42.js";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "EncounterRow",
  __ssrInlineRender: true,
  props: {
    summoner_match: {
      type: Object,
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
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c;
      const _component_VImg = resolveComponent("VImg");
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: `${((_a = props.summoner_match) == null ? void 0 : _a.won) ? "bg-blue-1" : "bg-red-1"}  flex items-center justify-start p-2 ${__props.is_reverse ? "flex-row-reverse " : ""}`
      }, _attrs))}><div>`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlChampionHelper)((_b = __props.summoner_match.champion) == null ? void 0 : _b.img_url),
        class: "w-16 h-16"
      }, null, _parent));
      _push(`</div><div class="mx-4 text-gray-5"><div class="flex"><div class="text-gray-5 font-bold">${ssrInterpolate(__props.summoner_match.kills.toFixed(1))}</div><div class="text-gray-4 mx-1">/</div><div class="text-red-3 font-bold">${ssrInterpolate(__props.summoner_match.deaths.toFixed(1))}</div><div class="text-gray-4 mx-1">/</div><div class="text-gray-5 font-bold">${ssrInterpolate(__props.summoner_match.assists.toFixed(1))}</div></div><div class="font-bold text-2xl">${ssrInterpolate((_c = __props.summoner_match.kda) == null ? void 0 : _c.toFixed(2))}:1 KDA </div><div> P/Kill ${ssrInterpolate((__props.summoner_match.kill_participation * 100).toFixed(0))}% </div></div></div>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/EncounterRow.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "EncounterPart",
  __ssrInlineRender: true,
  props: {
    encounter_data: {},
    summoner: {},
    is_with: { type: Boolean },
    encounter: {}
  },
  setup(__props) {
    const props = __props;
    const has_won = (match) => {
      return match.participants.filter((participant) => {
        return participant.summoner_id === props.summoner.id;
      })[0].won;
    };
    return (_ctx, _push, _parent, _attrs) => {
      if (_ctx.encounter_data.matches.length > 0) {
        _push(`<!--[--><div class="grid grid-cols-2 text-gray-5"><div>`);
        _push(ssrRenderComponent(_sfc_main$3, {
          with_summoner_name: true,
          summoner_stats: _ctx.encounter_data.summoner_stats,
          summoner: _ctx.summoner,
          color: "blue"
        }, null, _parent));
        _push(`</div><div>`);
        _push(ssrRenderComponent(_sfc_main$3, {
          with_summoner_name: true,
          is_reverse: true,
          summoner_stats: _ctx.encounter_data.encounter_stats,
          summoner: _ctx.encounter,
          color: _ctx.is_with ? "blue" : "red"
        }, null, _parent));
        _push(`</div></div><div><!--[-->`);
        ssrRenderList(_ctx.encounter_data.matches, (match) => {
          _push(`<div class="grid grid-cols-3 my-4"><!--[-->`);
          ssrRenderList(match.participants, (participant) => {
            _push(`<!--[-->`);
            if (participant.summoner_id === _ctx.summoner.id) {
              _push(ssrRenderComponent(_sfc_main$2, {
                is_reverse: false,
                summoner_match: participant,
                class: "mr-4"
              }, null, _parent));
            } else {
              _push(`<!---->`);
            }
            _push(`<!--]-->`);
          });
          _push(`<!--]--><div class="${ssrRenderClass(`${!match.participants || has_won(match) ? " bg-blue-1" : "bg-red-1"} text-gray-5 flex flex-col items-center justify-center cursor-pointer`)}"><div>${ssrInterpolate(match.queue.description)}</div><div>${ssrInterpolate(unref(moment)(match.match_end).fromNow())}</div><div>${ssrInterpolate(!match.participants || has_won(match) ? "Victory" : "Defeat")}</div><div>${ssrInterpolate(match.match_duration)}</div></div><!--[-->`);
          ssrRenderList(match.participants, (participant) => {
            _push(`<!--[-->`);
            if (participant.summoner_id === _ctx.encounter.id) {
              _push(ssrRenderComponent(_sfc_main$2, {
                is_reverse: true,
                summoner_match: participant,
                class: "ml-4"
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
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/EncounterPart.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Encounter",
  __ssrInlineRender: true,
  props: {
    encounter: {},
    vs_: {},
    with_: {}
  },
  setup(__props) {
    const tab = ref("with");
    const summoner = getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTabs = resolveComponent("VTabs");
      const _component_VTab = resolveComponent("VTab");
      const _component_VWindow = resolveComponent("VWindow");
      const _component_VWindowItem = resolveComponent("VWindowItem");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
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
                    encounter_data: _ctx.with_,
                    summoner: unref(summoner),
                    encounter: _ctx.encounter,
                    is_with: true
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$1, {
                      encounter_data: _ctx.with_,
                      summoner: unref(summoner),
                      encounter: _ctx.encounter,
                      is_with: true
                    }, null, 8, ["encounter_data", "summoner", "encounter"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_VWindowItem, { value: "vs" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$1, {
                    encounter_data: _ctx.vs_,
                    summoner: unref(summoner),
                    encounter: _ctx.encounter,
                    is_with: false
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$1, {
                      encounter_data: _ctx.vs_,
                      summoner: unref(summoner),
                      encounter: _ctx.encounter,
                      is_with: false
                    }, null, 8, ["encounter_data", "summoner", "encounter"])
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
                    encounter_data: _ctx.with_,
                    summoner: unref(summoner),
                    encounter: _ctx.encounter,
                    is_with: true
                  }, null, 8, ["encounter_data", "summoner", "encounter"])
                ]),
                _: 1
              }),
              createVNode(_component_VWindowItem, { value: "vs" }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$1, {
                    encounter_data: _ctx.vs_,
                    summoner: unref(summoner),
                    encounter: _ctx.encounter,
                    is_with: false
                  }, null, 8, ["encounter_data", "summoner", "encounter"])
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
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Encounter.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
