import { resolveComponent, mergeProps, unref, withCtx, createTextVNode, toDisplayString, useSSRContext, createVNode } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { b as getVersion, g as getSummoner, c as getRouteParams, n as navigateToEncounter, d as navigateToMatch, _ as _sfc_main$3 } from "./SummonerHeader-0b1a75fc.js";
import { _ as _sfc_main$4 } from "./SummonerStats-6fa23ddc.js";
import moment from "moment";
import { P as PrimaryButton } from "./PrimaryButton-d82933f3.js";
import { u as urlChampionHelper, a as urlItemHelper } from "./url_helpers-8bee5ae0.js";
import { Link, usePage } from "@inertiajs/vue3";
import { _ as _sfc_main$5 } from "./Pagination-e3f93c8f.js";
import "./InputError-be673cc6.js";
import "./ResponsiveNavLink-2d39c768.js";
import "vue3-datepicker";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$2 = {
  __name: "MatchesRowPart",
  __ssrInlineRender: true,
  props: {
    participant: {
      type: Object
    },
    summoner_encounter_count: {
      type: Object
    },
    is_self: {
      type: Boolean
    }
  },
  setup(__props) {
    let version = getVersion();
    let summoner = getSummoner();
    getRouteParams();
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b;
      const _component_VImg = resolveComponent("VImg");
      const _component_VIcon = resolveComponent("VIcon");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex items-center justify-center h-5 mb-1 min-w-[12rem]" }, _attrs))}><div>`);
      _push(ssrRenderComponent(_component_VImg, {
        alt: ((_a = __props.participant.champion) == null ? void 0 : _a.name) ?? "",
        src: unref(urlChampionHelper)(unref(version), (_b = __props.participant.champion) == null ? void 0 : _b.img_url),
        class: "w-5 h-5 " + (__props.is_self ? "rounded-full" : "rounded")
      }, null, _parent));
      _push(`</div><div class="flex ml-1 w-8 justify-center text-sm">`);
      if (__props.is_self) {
        _push(`<div class="w-3 h-3 flex items-center justify-center">`);
        _push(ssrRenderComponent(_component_VIcon, {
          icon: "fa fa-user-o",
          class: "w-3 h-3"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!--[-->`);
        if (__props.summoner_encounter_count.hasOwnProperty(__props.participant.summoner_id)) {
          _push(ssrRenderComponent(unref(Link), {
            href: "#",
            onClick: ($event) => unref(navigateToEncounter)(unref(summoner).id, __props.participant.summoner_id)
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(__props.summoner_encounter_count[__props.participant.summoner_id])}`);
              } else {
                return [
                  createTextVNode(toDisplayString(__props.summoner_encounter_count[__props.participant.summoner_id]), 1)
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<div> 1 </div>`);
        }
        _push(`<!--]-->`);
      }
      _push(`</div><div class="ml-1 truncate w-64 cursor-pointer">`);
      _push(ssrRenderComponent(unref(Link), { href: "#" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a2, _b2;
          if (_push2) {
            _push2(`${ssrInterpolate((_a2 = __props.participant.summoner) == null ? void 0 : _a2.name)}`);
          } else {
            return [
              createTextVNode(toDisplayString((_b2 = __props.participant.summoner) == null ? void 0 : _b2.name), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div>`);
    };
  }
};
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchesRowPart.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = {
  __name: "MatchesRow",
  __ssrInlineRender: true,
  props: {
    summoner_match: {
      type: Object
    },
    summoner_encounter_count: {
      type: Object
    }
  },
  setup(__props) {
    const version = usePage().props.version;
    const summoner = usePage().props.summoner;
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h;
      const _component_VImg = resolveComponent("VImg");
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: `${__props.summoner_match.won ? "bg-blue-primary" : "bg-red-primary"}  p-4 my-2 flex rounded`
      }, _attrs))}><div class="w-1/5 flex flex-col justify-center space-y-1"><div>${ssrInterpolate((_b = (_a = __props.summoner_match.match) == null ? void 0 : _a.queue) == null ? void 0 : _b.description)}</div><div>${ssrInterpolate(unref(moment)((_c = __props.summoner_match.match) == null ? void 0 : _c.match_end).fromNow())}</div><div>${ssrInterpolate(__props.summoner_match.won ? "Victory" : "Defeat")}</div><div>${ssrInterpolate((_d = __props.summoner_match.match) == null ? void 0 : _d.match_duration)}</div></div><div class="ml-4 w-1/5 flex flex-col justify-center"><div class="w-full flex"><div class="w-16 h-16">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlChampionHelper)(unref(version), (_e = __props.summoner_match.champion) == null ? void 0 : _e.img_url),
        class: "w-16 h-16"
      }, null, _parent));
      _push(`</div><div class="ml-4 text-xl flex justify-center items-center flex-col"><div class="flex"><div class="text-white font-bold">${ssrInterpolate(__props.summoner_match.kills)}</div><div class="text-gray-500 mx-1">/</div><div class="text-red-700 font-bold">${ssrInterpolate(__props.summoner_match.deaths)}</div><div class="text-gray-500 mx-1">/</div><div class="text-white font-bold">${ssrInterpolate(__props.summoner_match.assists)}</div></div><div>${ssrInterpolate((_f = __props.summoner_match.kda) == null ? void 0 : _f.toFixed(2))}:1 KDA </div></div></div><div class="flex mt-4 space-x-2"><!--[-->`);
      ssrRenderList(__props.summoner_match.items, (item) => {
        _push(`<div class="w-8 h-8">`);
        _push(ssrRenderComponent(_component_VImg, {
          src: unref(urlItemHelper)(unref(version), item.img_url),
          class: "w-8 h-8"
        }, null, _parent));
        _push(`</div>`);
      });
      _push(`<!--]--></div></div><div class="w-1/5 flex flex-col justify-center space-y-1"><div>P/Kill ${ssrInterpolate((((_g = __props.summoner_match) == null ? void 0 : _g.kill_participation) * 100).toFixed(0))}%</div><div>Control Ward nc</div><div>CS ${ssrInterpolate(__props.summoner_match.minions_killed)} (${ssrInterpolate((__props.summoner_match.minions_killed / unref(moment)((_h = __props.summoner_match.match) == null ? void 0 : _h.match_duration).minutes()).toFixed(1))}) </div><div>Avg Rank nc</div></div><div class="flex w-1/4 justify-self-end"><div class="grid grid-cols-2"><div><!--[-->`);
      ssrRenderList(__props.summoner_match.other_participants, (participant) => {
        _push(`<!--[-->`);
        if (__props.summoner_match.won === participant.won) {
          _push(ssrRenderComponent(_sfc_main$2, {
            key: participant.id,
            participant,
            summoner_encounter_count: __props.summoner_encounter_count,
            is_self: __props.summoner_match.summoner_id === participant.summoner_id,
            version: ""
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div><div><!--[-->`);
      ssrRenderList(__props.summoner_match.other_participants, (participant) => {
        _push(`<!--[-->`);
        if (__props.summoner_match.won !== participant.won) {
          _push(ssrRenderComponent(_sfc_main$2, {
            key: participant.id,
            participant,
            summoner_encounter_count: __props.summoner_encounter_count,
            is_self: __props.summoner_match.summoner_id === participant.summoner_id
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div></div></div><div class="flex justify-center ml-6 items-center w-1/5">`);
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: ($event) => unref(navigateToMatch)(unref(summoner).id, __props.summoner_match.match_id)
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Link), { href: "#" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(` Go `);
                } else {
                  return [
                    createTextVNode(" Go ")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(Link), { href: "#" }, {
                default: withCtx(() => [
                  createTextVNode(" Go ")
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchesRow.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Matches",
  __ssrInlineRender: true,
  props: {
    matches: {
      type: MatchesPaginatedInterface,
      required: true
    },
    summoner_stats: {
      type: SummonerStatsInterface,
      required: true
    },
    summoner_encounter_count: {
      type: SummonerEncounterCountInterface,
      required: true
    }
  },
  setup(__props) {
    const summoner = getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$3, { tab: "Matches" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$4, {
        summoner: unref(summoner),
        summoner_stats: __props.summoner_stats,
        justify: "start"
      }, null, _parent));
      _push(`<!--[-->`);
      ssrRenderList(__props.matches.data, (match) => {
        _push(`<div class="flex flex-col">`);
        _push(ssrRenderComponent(_sfc_main$1, {
          key: match.id,
          summoner_match: match,
          summoner_encounter_count: __props.summoner_encounter_count
        }, null, _parent));
        _push(`</div>`);
      });
      _push(`<!--]-->`);
      _push(ssrRenderComponent(_sfc_main$5, {
        links: __props.matches.links
      }, null, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Matches.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
