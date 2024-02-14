import { defineComponent, resolveComponent, mergeProps, unref, withCtx, createVNode, toDisplayString, useSSRContext, ref, computed, watch, createTextVNode, openBlock, createBlock, onMounted } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
import moment from "moment";
import { g as getSummoner, u as urlChampionHelper, a as urlProPlayerHelper, w as withoutTagLine, h as urlSummonerSpellHelper, i as urlPerkHelper, j as urlItemHelper } from "./SummonerHeader-7f8813f6.js";
import { Doughnut } from "vue-chartjs";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import { P as PrimaryButton } from "./PrimaryButton-cbcf38ef.js";
import { a as navigateToMatch } from "./router_helpers-0edbd391.js";
const _sfc_main$9 = /* @__PURE__ */ defineComponent({
  __name: "MatchesRowPart",
  __ssrInlineRender: true,
  props: {
    participant: {},
    summoner_encounter_count: {},
    is_self: { type: Boolean }
  },
  setup(__props) {
    getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c;
      const _component_VImg = resolveComponent("VImg");
      const _component_VIcon = resolveComponent("VIcon");
      const _component_v_tooltip = resolveComponent("v-tooltip");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex items-center justify-center h-5 mb-1 min-w-[12rem]" }, _attrs))}><div>`);
      _push(ssrRenderComponent(_component_VImg, {
        alt: ((_a = _ctx.participant.champion) == null ? void 0 : _a.name) ?? "",
        src: unref(urlChampionHelper)((_b = _ctx.participant.champion) == null ? void 0 : _b.img_url),
        class: "w-5 h-5 " + (_ctx.is_self ? "rounded-full" : "rounded")
      }, null, _parent));
      _push(`</div><div class="flex ml-1 w-8 justify-center text-sm">`);
      if (_ctx.is_self) {
        _push(`<div class="w-3 h-3 flex items-center justify-center">`);
        _push(ssrRenderComponent(_component_VIcon, {
          icon: "fa fa-user-o",
          class: "w-3 h-3"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<a href="#">`);
        if (_ctx.summoner_encounter_count.hasOwnProperty(_ctx.participant.summoner_id)) {
          _push(`<!--[-->${ssrInterpolate(_ctx.summoner_encounter_count[_ctx.participant.summoner_id])}<!--]-->`);
        } else {
          _push(`<!--[--> 1 <!--]-->`);
        }
        _push(`</a>`);
      }
      _push(`</div>`);
      if (_ctx.participant.summoner.pro_player) {
        _push(`<div class="flex ml-1 justify-center text-xs"><a${ssrRenderAttr("href", unref(urlProPlayerHelper)(_ctx.participant.summoner.pro_player.slug))}><div class="bg-purple-800 py-0.5 px-1 rounded"> PRO `);
        _push(ssrRenderComponent(_component_v_tooltip, {
          activator: "parent",
          location: "bottom",
          class: "text-center"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            var _a2, _b2, _c2, _d;
            if (_push2) {
              _push2(`<p${_scopeId}>${ssrInterpolate((_a2 = _ctx.participant.summoner.pro_player) == null ? void 0 : _a2.team_name)}</p><p${_scopeId}>${ssrInterpolate((_b2 = _ctx.participant.summoner.pro_player) == null ? void 0 : _b2.name)}</p>`);
            } else {
              return [
                createVNode("p", null, toDisplayString((_c2 = _ctx.participant.summoner.pro_player) == null ? void 0 : _c2.team_name), 1),
                createVNode("p", null, toDisplayString((_d = _ctx.participant.summoner.pro_player) == null ? void 0 : _d.name), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div></a></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="ml-1 truncate w-64 cursor-pointer">${ssrInterpolate(unref(withoutTagLine)((_c = _ctx.participant.summoner) == null ? void 0 : _c.name))}</div></div>`);
    };
  }
});
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchesRowPart.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const _sfc_main$8 = /* @__PURE__ */ defineComponent({
  __name: "MatchTable",
  __ssrInlineRender: true,
  props: {
    participants: {},
    summoner_encounter_count: {},
    duration_minutes: {},
    won: { type: Boolean }
  },
  setup(__props) {
    const props = __props;
    const summoner = getSummoner();
    const getTrColor = (participant) => {
      if (summoner.id === participant.summoner_id) {
        return participant.won ? "bg-blue-2" : "bg-red-2";
      } else {
        return participant.won ? "bg-blue-1" : "bg-red-1";
      }
    };
    let max_total_damage_dealt = 0;
    let max_total_damage_taken = 0;
    for (let participant of props.participants) {
      if (participant.total_damage_dealt_to_champions > max_total_damage_dealt) {
        max_total_damage_dealt = participant.total_damage_dealt_to_champions;
      }
      if (participant.total_damage_taken > max_total_damage_taken) {
        max_total_damage_taken = participant.total_damage_taken;
      }
    }
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VImg = resolveComponent("VImg");
      const _component_VIcon = resolveComponent("VIcon");
      const _component_VProgressLinear = resolveComponent("VProgressLinear");
      _push(`<table${ssrRenderAttrs(mergeProps({ class: "text-gray-5" }, _attrs))}><thead><tr><th class="${ssrRenderClass(`${_ctx.won ? "text-blue-3" : "text-red-3"} font-bold p-2`)}">${ssrInterpolate(_ctx.won ? "Victory" : "Defeat")}</th><th class="p-2">Seen</th><th class="p-2">Rank</th><th class="p-2">KDA</th><th class="p-2">Damage</th><th class="p-2">CS</th><th class="p-2">Item</th></tr></thead><tbody><!--[-->`);
      ssrRenderList(_ctx.participants, (participant, index) => {
        var _a, _b, _c;
        _push(`<!--[-->`);
        if (participant.won === _ctx.won) {
          _push(`<tr class="${ssrRenderClass(`${getTrColor(participant)} border-gray-500 border-b-2 text-left`)}"><td class="w-64 px-3"><div class="flex items-center justify-start pl-2 space-x-2"><div class="w-12 h-12 relative">`);
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlChampionHelper)((_a = participant.champion) == null ? void 0 : _a.img_url),
            class: "w-12 h-12 rounded-full"
          }, null, _parent));
          _push(`<div class="absolute -bottom-1 right-0 bg-gray-1 rounded-full px-0.5">${ssrInterpolate(participant.champ_level)}</div></div><div class="flex flex-col ml-1">`);
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlSummonerSpellHelper)(participant.summoner_spell1.img_url),
            class: "w-6 h-6 rounded"
          }, null, _parent));
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlSummonerSpellHelper)(participant.summoner_spell2.img_url),
            class: "w-6 h-6 rounded mt-1"
          }, null, _parent));
          _push(`</div><div class="flex flex-col ml-1">`);
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlPerkHelper)(participant.perks.primary_selection.img_url),
            class: "w-6 h-6 bg-black rounded-full"
          }, null, _parent));
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlPerkHelper)(participant.perks.sub_style.img_url),
            class: "w-6 h-6 mt-1"
          }, null, _parent));
          _push(`</div><div class="flex flex-col font-medium"><div class="cursor-pointer">${ssrInterpolate(unref(withoutTagLine)((_b = participant == null ? void 0 : participant.summoner) == null ? void 0 : _b.name))}</div></div></div></td><td class="text-center">`);
          if (participant.summoner_id === unref(summoner).id) {
            _push(`<div class="flex items-center justify-center">`);
            _push(ssrRenderComponent(_component_VIcon, {
              icon: "fa fa-user-o",
              class: "w-3 h-3"
            }, null, _parent));
            _push(`</div>`);
          } else {
            _push(`<a href="#">`);
            if (_ctx.summoner_encounter_count.hasOwnProperty(participant.summoner_id)) {
              _push(`<!--[-->${ssrInterpolate(_ctx.summoner_encounter_count[participant.summoner_id])}<!--]-->`);
            } else {
              _push(`<!--[--> 1 <!--]-->`);
            }
            _push(`</a>`);
          }
          _push(`</td><td class="text-center">`);
          if (participant.summoner.solo_q) {
            _push(`<!--[-->${ssrInterpolate(participant.summoner.solo_q.tier)} ${ssrInterpolate(participant.summoner.solo_q.rank)}<!--]-->`);
          } else {
            _push(`<!--[--> lvl ${ssrInterpolate(participant.summoner.summoner_level)}<!--]-->`);
          }
          _push(`</td><td class="w-64 py-1 px-3"><div class="ml-4 text-xl flex justify-center items-center flex-col"><div class="flex"><div class="text-gray-5 font-bold">${ssrInterpolate(participant.kills)}</div><div class="text-gray-4 mx-1">/</div><div class="text-red-3 font-bold">${ssrInterpolate(participant.deaths)}</div><div class="text-gray-4 mx-1">/</div><div class="text-gray-5 font-bold">${ssrInterpolate(participant.assists)}</div></div><div>${ssrInterpolate((_c = participant.kda) == null ? void 0 : _c.toFixed(1))}:1 KDA </div></div></td><td class="w-fit py-1 px-3"><div class="flex w-48 text-center"><div class="w-24">${ssrInterpolate(participant.total_damage_dealt_to_champions)} `);
          _push(ssrRenderComponent(_component_VProgressLinear, {
            "model-value": Math.round(participant.total_damage_dealt_to_champions / unref(max_total_damage_dealt) * 100),
            color: "red-lighten-2",
            height: 10
          }, null, _parent));
          _push(`</div><div class="ml-4 w-24">${ssrInterpolate(participant.total_damage_taken)} `);
          _push(ssrRenderComponent(_component_VProgressLinear, {
            "model-value": Math.round(participant.total_damage_taken / unref(max_total_damage_taken) * 100),
            color: "blue-grey-lighten-1",
            height: 10
          }, null, _parent));
          _push(`</div></div></td><td class="w-fit py-1 px-3"><div class="text-center"><div>${ssrInterpolate(participant.minions_killed)}</div><div>${ssrInterpolate((participant.minions_killed / _ctx.duration_minutes).toFixed(1))}/m</div></div></td><td class="py-1 w-72 px-3"><div class="flex space-x-2"><!--[-->`);
          ssrRenderList(participant.items, (item) => {
            _push(`<div class="w-8 h-8">`);
            _push(ssrRenderComponent(_component_VImg, {
              src: unref(urlItemHelper)(item.img_url),
              class: "w-8 h-8"
            }, null, _parent));
            _push(`</div>`);
          });
          _push(`<!--]--></div></td></tr>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></tbody></table>`);
    };
  }
});
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchTable.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
const getDurationMinutes = (duration) => {
  if (!duration)
    return 0;
  const date_ = /* @__PURE__ */ new Date("2000-01-01 " + duration);
  return date_.getHours() * 60 + date_.getMinutes();
};
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "LinearStat",
  __ssrInlineRender: true,
  props: {
    participants: {},
    key_stat: {},
    has_won: { type: Boolean },
    description: {},
    height: {}
  },
  setup(__props) {
    const props = __props;
    const getParticipantStat = (participant) => {
      let stat = participant[props.key_stat];
      if (typeof stat == "number") {
        return stat;
      } else {
        return 0;
      }
    };
    let my_team_stat = 0;
    let other_team_stat = 0;
    for (let participant of props.participants) {
      if (participant.won === props.has_won) {
        my_team_stat += getParticipantStat(participant);
      } else {
        other_team_stat += getParticipantStat(participant);
      }
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex relative items-center h-full" }, _attrs))}><div class="${ssrRenderClass(`${_ctx.has_won ? "bg-red-3" : "bg-blue-3"} flex items-center justify-end text-right w-full pr-3 ${_ctx.height}`)}"><strong>${ssrInterpolate(unref(other_team_stat))}</strong></div><div class="${ssrRenderClass(`${_ctx.has_won ? "bg-blue-3" : "bg-red-3"} absolute text-left left-0 pl-3 flex items-center justify-start ${_ctx.height}`)}" style="${ssrRenderStyle({ width: unref(my_team_stat) / (unref(my_team_stat) + unref(other_team_stat)) * 100 + "%" })}">${ssrInterpolate(unref(my_team_stat))}</div><div class="absolute text-center w-full">${ssrInterpolate(_ctx.description)}</div></div>`);
    };
  }
});
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/LinearStat.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "CircularStat",
  __ssrInlineRender: true,
  props: {
    participants: {},
    key_stat: {},
    description: {},
    split_number: { type: Boolean }
  },
  setup(__props) {
    const props = __props;
    const handleSplitNumberToNumber = (number) => {
      if (props.split_number) {
        return number / 1e3;
      } else {
        return number;
      }
    };
    const handleSplitNumberToString = (number) => {
      if (props.split_number) {
        number = (number / 1e3).toFixed(2);
      }
      return number.toString().replace(".", ",") + (props.split_number ? "k" : "");
    };
    const getParticipantStat = (participant) => {
      let stat = participant[props.key_stat];
      if (typeof stat == "number") {
        return stat;
      } else {
        return 0;
      }
    };
    let max_stat = 0;
    let won_stat = 0;
    let lose_stat = 0;
    for (let participant of props.participants) {
      if (getParticipantStat(participant) > max_stat) {
        max_stat = getParticipantStat(participant);
      }
      if (participant.won) {
        won_stat += getParticipantStat(participant);
      } else {
        lose_stat += getParticipantStat(participant);
      }
    }
    let chart_data = {
      datasets: [
        {
          data: [handleSplitNumberToNumber(lose_stat), handleSplitNumberToNumber(won_stat)],
          backgroundColor: [
            "#E57373",
            "#64B5F6"
          ]
        }
      ]
    };
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VImg = resolveComponent("VImg");
      const _component_VProgressLinear = resolveComponent("VProgressLinear");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex flex-col justify-center p-2" }, _attrs))}><div class="text-center">${ssrInterpolate(_ctx.description)}</div><div class="flex space-x-2 mt-2"><div class="w-1/3 flex flex-col space-y-1"><!--[-->`);
      ssrRenderList(_ctx.participants, (participant, idx) => {
        _push(`<!--[-->`);
        if (participant.won) {
          _push(`<div class="flex items-center"><div>`);
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlChampionHelper)(participant.champion.img_url),
            class: "w-6 h-6"
          }, null, _parent));
          _push(`</div><div class="w-full ml-1">`);
          _push(ssrRenderComponent(_component_VProgressLinear, {
            "model-value": getParticipantStat(participant) / unref(max_stat) * 100,
            color: "blue-lighten-2",
            height: 25
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`<strong class="ml-auto mr-3"${_scopeId}>${ssrInterpolate(handleSplitNumberToString(getParticipantStat(participant)))}</strong>`);
              } else {
                return [
                  createVNode("strong", { class: "ml-auto mr-3" }, toDisplayString(handleSplitNumberToString(getParticipantStat(participant))), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`</div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div><div class="w-1/3 h-full">`);
      _push(ssrRenderComponent(unref(Doughnut), {
        data: unref(chart_data),
        options: { responsive: true, maintainAspectRatio: false }
      }, null, _parent));
      _push(`</div><div class="w-1/3 flex flex-col space-y-1"><!--[-->`);
      ssrRenderList(_ctx.participants, (participant, idx) => {
        _push(`<!--[-->`);
        if (!participant.won) {
          _push(`<div class="flex items-center"><div>`);
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlChampionHelper)(participant.champion.img_url),
            class: "w-6 h-6"
          }, null, _parent));
          _push(`</div><div class="w-full ml-1">`);
          _push(ssrRenderComponent(_component_VProgressLinear, {
            "model-value": getParticipantStat(participant) / unref(max_stat) * 100,
            color: "red-lighten-2",
            height: 25
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`<strong class="ml-auto mr-3"${_scopeId}>${ssrInterpolate(handleSplitNumberToString(getParticipantStat(participant)))}</strong>`);
              } else {
                return [
                  createVNode("strong", { class: "ml-auto mr-3" }, toDisplayString(handleSplitNumberToString(getParticipantStat(participant))), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`</div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div></div></div>`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/CircularStat.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
var EventType = /* @__PURE__ */ ((EventType2) => {
  EventType2["PAUSE_END"] = "PAUSE_END";
  EventType2["OTHER"] = "OTHER";
  EventType2["TOWER"] = "TOWER";
  EventType2["ITEM_UNDO"] = "ITEM_UNDO";
  EventType2["MINION"] = "MINION";
  EventType2["GAME_END"] = "GAME_END";
  EventType2["CHAMPION_KILL"] = "CHAMPION_KILL";
  EventType2["CHAMPION_SPECIAL_KILL"] = "CHAMPION_SPECIAL_KILL";
  EventType2["LEVEL_UP"] = "LEVEL_UP";
  EventType2["ITEM_DESTROYED"] = "ITEM_DESTROYED";
  EventType2["WARD_PLACED"] = "WARD_PLACED";
  EventType2["BUILDING_KILL"] = "BUILDING_KILL";
  EventType2["PORO_KING_SUMMON"] = "PORO_KING_SUMMON";
  EventType2["ITEM_PURCHASED"] = "ITEM_PURCHASED";
  EventType2["SKILL_LEVEL_UP"] = "SKILL_LEVEL_UP";
  EventType2["ITEM_SOLD"] = "ITEM_SOLD";
  return EventType2;
})(EventType || {});
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "MatchDetailItemsBuild",
  __ssrInlineRender: true,
  props: {
    selected_participant: {},
    first_frame_with_events: {}
  },
  setup(__props) {
    const timestamp_to_hours_minutes_seconds = (timestamp) => {
      const duration = moment.duration(timestamp - 6e4, "milliseconds");
      const hours = duration.hours();
      const minutes = duration.minutes();
      const seconds = duration.seconds();
      return [
        hours && hours.toString().padStart(2, "0"),
        minutes.toString().padStart(2, "0"),
        seconds.toString().padStart(2, "0")
      ].filter(Boolean).join(":");
    };
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VIcon = resolveComponent("VIcon");
      const _component_VImg = resolveComponent("VImg");
      _push(`<!--[--><div class="bg-gray-1"> Item Builds </div><div class="flex mt-2 flex-wrap"><!--[-->`);
      ssrRenderList(_ctx.selected_participant.frames, (frame, idx) => {
        _push(`<!--[-->`);
        if (frame.item_events.length > 0) {
          _push(`<!--[-->`);
          if (idx > _ctx.first_frame_with_events) {
            _push(`<div class="flex mb-10 items-center"><div class="flex items-center h-10 border-8 border-gray-900 bg-gray-900">`);
            _push(ssrRenderComponent(_component_VIcon, {
              icon: "fa fa-arrow-right",
              class: "text-gray-4 h-5"
            }, null, _parent));
            _push(`</div></div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<div class="flex flex-col items-center relative mb-10"><div class="flex items-center border-gray-900 border-4 rounded"><!--[-->`);
          ssrRenderList(frame.item_events, (item_event) => {
            _push(`<!--[-->`);
            if (item_event.type === unref(EventType).ITEM_PURCHASED) {
              _push(`<div class="relative border-gray-900 border-4">`);
              _push(ssrRenderComponent(_component_VImg, {
                src: unref(urlItemHelper)(item_event.item.img_url),
                class: "w-8 h-8"
              }, null, _parent));
              if (item_event.item_count > 1) {
                _push(`<div class="text-center flex justify-center items-center text-sm absolute bottom-0 right-0 bg-gray-800 font-bold rounded w-[17px] h-[17px] border-4 border-gray-800"><span>${ssrInterpolate(item_event.item_count)}</span></div>`);
              } else {
                _push(`<!---->`);
              }
              _push(`</div>`);
            } else if (item_event.type === unref(EventType).ITEM_SOLD) {
              _push(`<div class="relative rounded border-gray-900 border-4">`);
              _push(ssrRenderComponent(_component_VImg, {
                src: unref(urlItemHelper)(item_event.item.img_url),
                class: "w-8 h-8 opacity-75"
              }, null, _parent));
              _push(`<div class="absolute -bottom-0.5 -right-0.5">`);
              _push(ssrRenderComponent(_component_VIcon, {
                icon: "fa fa-times",
                class: "text-red"
              }, null, _parent));
              _push(`</div>`);
              if (item_event.item_count > 1) {
                _push(`<div class="text-center flex justify-center items-center text-sm absolute bottom-0 right-0 bg-gray-800 font-bold rounded w-[17px] h-[17px] border-4 border-gray-800"><span>${ssrInterpolate(item_event.item_count)}</span></div>`);
              } else {
                _push(`<!---->`);
              }
              _push(`</div>`);
            } else {
              _push(`<!---->`);
            }
            _push(`<!--]-->`);
          });
          _push(`<!--]--></div><div class="text-center mt-1 absolute -bottom-7">${ssrInterpolate(timestamp_to_hours_minutes_seconds(frame.current_timestamp))}</div></div><!--]-->`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div><!--]-->`);
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchDetailItemsBuild.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "MatchDetailSkillBuild",
  __ssrInlineRender: true,
  props: {
    ordered_level_up_skills: {},
    selected_participant: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><div class="bg-gray-1 mt-4"> Skill Build </div><div class="mt-2"><div class="flex space-x-2"><!--[-->`);
      ssrRenderList(_ctx.ordered_level_up_skills, (ordered_level_up_skill) => {
        _push(`<!--[-->`);
        if (ordered_level_up_skill == 1) {
          _push(`<div class="font-bold rounded px-3 py-1.5 text-blue-4 bg-zinc-700"> Q </div>`);
        } else {
          _push(`<!---->`);
        }
        if (ordered_level_up_skill == 2) {
          _push(`<div class="font-bold rounded px-3 py-1.5 text-green-400 bg-zinc-700"> W </div>`);
        } else {
          _push(`<!---->`);
        }
        if (ordered_level_up_skill == 3) {
          _push(`<div class="font-bold rounded px-3 py-1.5 text-orange-400 bg-zinc-700"> E </div>`);
        } else {
          _push(`<!---->`);
        }
        if (ordered_level_up_skill == 4) {
          _push(`<div class="font-bold rounded px-3 py-1.5 bg-indigo-500"> R </div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div><div class="flex mt-4 space-x-2"><!--[-->`);
      ssrRenderList(_ctx.selected_participant.frames, (frame, idx) => {
        _push(`<!--[--><!--[-->`);
        ssrRenderList(frame.level_up_skill_events, (level_up_skill_event) => {
          _push(`<!--[-->`);
          if (level_up_skill_event.skill_slot == 1) {
            _push(`<div class="font-bold rounded px-3 py-1.5 text-blue-4 bg-zinc-700"> Q </div>`);
          } else {
            _push(`<!---->`);
          }
          if (level_up_skill_event.skill_slot == 2) {
            _push(`<div class="font-bold rounded px-3 py-1.5 text-green-400 bg-zinc-700"> W </div>`);
          } else {
            _push(`<!---->`);
          }
          if (level_up_skill_event.skill_slot == 3) {
            _push(`<div class="font-bold rounded px-3 py-1.5 text-orange-400 bg-zinc-700"> E </div>`);
          } else {
            _push(`<!---->`);
          }
          if (level_up_skill_event.skill_slot == 4) {
            _push(`<div class="font-bold rounded px-3 py-1.5 bg-indigo-500"> R </div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<!--]-->`);
        });
        _push(`<!--]--><!--]-->`);
      });
      _push(`<!--]--></div></div><!--]-->`);
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchDetailSkillBuild.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "MatchDetailRuneBuild",
  __ssrInlineRender: true,
  props: {
    selected_participant: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VImg = resolveComponent("VImg");
      _push(`<!--[--><div class="bg-gray-1 mt-4"> Runes </div><div class="mt-2 flex w-1/2"><div class="w-1/6 flex flex-col justify-end space-y-2 items-center"><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.primary_style.img_url),
        class: "w-10 h-10 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center mt-2">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.primary_selection.img_url),
        class: "w-10 h-10 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.primary_selection1.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.primary_selection2.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.primary_selection3.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="text-center">${ssrInterpolate(_ctx.selected_participant.perks.primary_style.name)}</div></div><div class="w-1/4 flex flex-col justify-end space-y-2 items-center"><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.sub_style.img_url),
        class: "w-10 h-10 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center mt-2">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.sub_selection1.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.sub_selection2.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="text-center">${ssrInterpolate(_ctx.selected_participant.perks.sub_style.name)}</div></div><div class="w-1/4 flex flex-col justify-end space-y-2 items-center"><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.offense.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.flex.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="flex items-center">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.selected_participant.perks.defense.img_url),
        class: "w-8 h-8 bg-gray-950 rounded-full"
      }, null, _parent));
      _push(`</div><div class="text-center"> Rune Stats </div></div></div><!--]-->`);
    };
  }
});
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchDetailRuneBuild.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "MatchDetailBuild",
  __ssrInlineRender: true,
  props: {
    summoner_match: {},
    match_detail: {}
  },
  setup(__props) {
    const props = __props;
    const selected_participant_id = ref(null);
    const selected_participant = ref(null);
    const ordered_level_up_skills = ref([]);
    const first_frame_with_events = ref(-1);
    const participant_options = computed(
      () => props.summoner_match.match.participants.map(({ id, summoner, champion }) => ({
        value: id,
        label: `${summoner.name} - ${champion.name}`
      }))
    );
    watch(selected_participant_id, (value) => {
      if (!props.match_detail)
        return;
      const participant = props.match_detail.find((detail) => detail.id === value);
      if (!participant)
        return;
      selected_participant.value = participant;
      ordered_level_up_skills.value = [];
      first_frame_with_events.value = -1;
      const count_skills = {};
      participant.frames.forEach((frame, idx) => {
        if (first_frame_with_events.value === -1 && frame.item_events.length) {
          first_frame_with_events.value = idx;
        }
        frame.level_up_skill_events.forEach(({ skill_slot }) => {
          count_skills[skill_slot] = (count_skills[skill_slot] || 0) + 1;
          if (count_skills[skill_slot] === 5) {
            ordered_level_up_skills.value.push(skill_slot);
            delete count_skills[skill_slot];
          }
        });
      });
      const skillSlots = Object.keys(count_skills).map(Number).filter((skillSlot) => skillSlot !== 4).sort((a, b) => count_skills[b] - count_skills[a]);
      ordered_level_up_skills.value.push(...skillSlots);
    });
    selected_participant_id.value = props.summoner_match.id;
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VAutocomplete = resolveComponent("VAutocomplete");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "my-1 bg-gray-1 p-4 rounded text-gray-200 w-full" }, _attrs))}><div>`);
      _push(ssrRenderComponent(_component_VAutocomplete, {
        modelValue: selected_participant_id.value,
        "onUpdate:modelValue": ($event) => selected_participant_id.value = $event,
        items: participant_options.value,
        label: "Select a participant",
        "item-value": "value",
        "item-title": "label",
        class: "w-80",
        density: "comfortable"
      }, null, _parent));
      _push(`</div>`);
      if (selected_participant.value) {
        _push(`<div><div>`);
        _push(ssrRenderComponent(_sfc_main$5, {
          selected_participant: selected_participant.value,
          first_frame_with_events: first_frame_with_events.value
        }, null, _parent));
        _push(ssrRenderComponent(_sfc_main$4, {
          selected_participant: selected_participant.value,
          ordered_level_up_skills: ordered_level_up_skills.value
        }, null, _parent));
        _push(ssrRenderComponent(_sfc_main$3, { selected_participant: selected_participant.value }, null, _parent));
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchDetailBuild.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "MatchDetail",
  __ssrInlineRender: true,
  props: {
    summoner_match: {},
    summoner_encounter_count: {}
  },
  setup(__props) {
    const props = __props;
    const tab = ref("overview");
    const match_detail = ref(null);
    ref(null);
    watch(tab, function(value) {
      if (value === "build" && match_detail.value === null) {
        axios.get(route("summoner.match.detail", {
          summoner_match_id: props.summoner_match.id,
          summoner_id: getSummoner().id
        })).then((response) => {
          match_detail.value = response.data.match_participants_detail;
          usePage().props.errors = {};
        }).catch((error) => {
          usePage().props.errors.api = error.response.data.api;
        });
      }
    });
    return (_ctx, _push, _parent, _attrs) => {
      const _component_v_tabs = resolveComponent("v-tabs");
      const _component_v_tab = resolveComponent("v-tab");
      const _component_v_window = resolveComponent("v-window");
      const _component_v_window_item = resolveComponent("v-window-item");
      const _component_v_progress_circular = resolveComponent("v-progress-circular");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "my-1 bg-gray-1 p-4 rounded text-gray-200 w-full" }, _attrs))}>`);
      _push(ssrRenderComponent(_component_v_tabs, {
        modelValue: tab.value,
        "onUpdate:modelValue": ($event) => tab.value = $event,
        "bg-color": "dark"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_component_v_tab, { value: "overview" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Overview`);
                } else {
                  return [
                    createTextVNode("Overview")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_v_tab, { value: "team_analysis" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Team analysis`);
                } else {
                  return [
                    createTextVNode("Team analysis")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_v_tab, { value: "build" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Build`);
                } else {
                  return [
                    createTextVNode("Build")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_component_v_tab, { value: "overview" }, {
                default: withCtx(() => [
                  createTextVNode("Overview")
                ]),
                _: 1
              }),
              createVNode(_component_v_tab, { value: "team_analysis" }, {
                default: withCtx(() => [
                  createTextVNode("Team analysis")
                ]),
                _: 1
              }),
              createVNode(_component_v_tab, { value: "build" }, {
                default: withCtx(() => [
                  createTextVNode("Build")
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_component_v_window, {
        modelValue: tab.value,
        "onUpdate:modelValue": ($event) => tab.value = $event
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_component_v_window_item, { value: "overview" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$8, {
                    summoner_encounter_count: _ctx.summoner_encounter_count,
                    duration_minutes: unref(getDurationMinutes)(_ctx.summoner_match.match.match_duration),
                    won: _ctx.summoner_match.won,
                    participants: _ctx.summoner_match.match.participants
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="flex justify-center flex-col my-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$7, {
                    class: "mx-auto w-2/3",
                    key_stat: "kills",
                    participants: _ctx.summoner_match.match.participants,
                    description: "Team kill",
                    has_won: _ctx.summoner_match.won
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$7, {
                    class: "mx-auto w-2/3 mt-2",
                    key_stat: "gold_earned",
                    participants: _ctx.summoner_match.match.participants,
                    description: "Team Gold",
                    has_won: _ctx.summoner_match.won
                  }, null, _parent3, _scopeId2));
                  _push3(`</div>`);
                  _push3(ssrRenderComponent(_sfc_main$8, {
                    summoner_encounter_count: _ctx.summoner_encounter_count,
                    duration_minutes: unref(getDurationMinutes)(_ctx.summoner_match.match.match_duration),
                    won: !_ctx.summoner_match.won,
                    participants: _ctx.summoner_match.match.participants
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$8, {
                      summoner_encounter_count: _ctx.summoner_encounter_count,
                      duration_minutes: unref(getDurationMinutes)(_ctx.summoner_match.match.match_duration),
                      won: _ctx.summoner_match.won,
                      participants: _ctx.summoner_match.match.participants
                    }, null, 8, ["summoner_encounter_count", "duration_minutes", "won", "participants"]),
                    createVNode("div", { class: "flex justify-center flex-col my-4" }, [
                      createVNode(_sfc_main$7, {
                        class: "mx-auto w-2/3",
                        key_stat: "kills",
                        participants: _ctx.summoner_match.match.participants,
                        description: "Team kill",
                        has_won: _ctx.summoner_match.won
                      }, null, 8, ["participants", "has_won"]),
                      createVNode(_sfc_main$7, {
                        class: "mx-auto w-2/3 mt-2",
                        key_stat: "gold_earned",
                        participants: _ctx.summoner_match.match.participants,
                        description: "Team Gold",
                        has_won: _ctx.summoner_match.won
                      }, null, 8, ["participants", "has_won"])
                    ]),
                    createVNode(_sfc_main$8, {
                      summoner_encounter_count: _ctx.summoner_encounter_count,
                      duration_minutes: unref(getDurationMinutes)(_ctx.summoner_match.match.match_duration),
                      won: !_ctx.summoner_match.won,
                      participants: _ctx.summoner_match.match.participants
                    }, null, 8, ["summoner_encounter_count", "duration_minutes", "won", "participants"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_v_window_item, { value: "team_analysis" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex justify-center"${_scopeId2}><div class="flex items-center justify-center"${_scopeId2}><div class="w-4 h-4 rounded-full bg-[#64B5F6] mr-3"${_scopeId2}></div><div${_scopeId2}> Winning team </div></div><div class="ml-10 flex items-center justify-center"${_scopeId2}><div class="w-4 h-4 rounded-full bg-[#E57373] mr-3"${_scopeId2}></div><div${_scopeId2}> Losing team </div></div></div><div class="grid grid-cols-2 gap-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$6, {
                    description: "Champion Kill",
                    split_number: false,
                    participants: _ctx.summoner_match.match.participants,
                    key_stat: "kills"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$6, {
                    description: "Gold Earned",
                    split_number: true,
                    participants: _ctx.summoner_match.match.participants,
                    key_stat: "gold_earned"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$6, {
                    description: "Damage Dealt to Champions",
                    split_number: true,
                    participants: _ctx.summoner_match.match.participants,
                    key_stat: "total_damage_dealt_to_champions"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$6, {
                    description: "Wards Placed",
                    split_number: false,
                    participants: _ctx.summoner_match.match.participants,
                    key_stat: "wards_placed"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$6, {
                    description: "Damage Taken",
                    split_number: true,
                    participants: _ctx.summoner_match.match.participants,
                    key_stat: "total_damage_taken"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$6, {
                    description: "CS",
                    split_number: false,
                    participants: _ctx.summoner_match.match.participants,
                    key_stat: "minions_killed"
                  }, null, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex justify-center" }, [
                      createVNode("div", { class: "flex items-center justify-center" }, [
                        createVNode("div", { class: "w-4 h-4 rounded-full bg-[#64B5F6] mr-3" }),
                        createVNode("div", null, " Winning team ")
                      ]),
                      createVNode("div", { class: "ml-10 flex items-center justify-center" }, [
                        createVNode("div", { class: "w-4 h-4 rounded-full bg-[#E57373] mr-3" }),
                        createVNode("div", null, " Losing team ")
                      ])
                    ]),
                    createVNode("div", { class: "grid grid-cols-2 gap-4" }, [
                      createVNode(_sfc_main$6, {
                        description: "Champion Kill",
                        split_number: false,
                        participants: _ctx.summoner_match.match.participants,
                        key_stat: "kills"
                      }, null, 8, ["participants"]),
                      createVNode(_sfc_main$6, {
                        description: "Gold Earned",
                        split_number: true,
                        participants: _ctx.summoner_match.match.participants,
                        key_stat: "gold_earned"
                      }, null, 8, ["participants"]),
                      createVNode(_sfc_main$6, {
                        description: "Damage Dealt to Champions",
                        split_number: true,
                        participants: _ctx.summoner_match.match.participants,
                        key_stat: "total_damage_dealt_to_champions"
                      }, null, 8, ["participants"]),
                      createVNode(_sfc_main$6, {
                        description: "Wards Placed",
                        split_number: false,
                        participants: _ctx.summoner_match.match.participants,
                        key_stat: "wards_placed"
                      }, null, 8, ["participants"]),
                      createVNode(_sfc_main$6, {
                        description: "Damage Taken",
                        split_number: true,
                        participants: _ctx.summoner_match.match.participants,
                        key_stat: "total_damage_taken"
                      }, null, 8, ["participants"]),
                      createVNode(_sfc_main$6, {
                        description: "CS",
                        split_number: false,
                        participants: _ctx.summoner_match.match.participants,
                        key_stat: "minions_killed"
                      }, null, 8, ["participants"])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_v_window_item, { value: "build" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (match_detail.value !== null) {
                    _push3(ssrRenderComponent(_sfc_main$2, {
                      summoner_match: _ctx.summoner_match,
                      match_detail: match_detail.value
                    }, null, _parent3, _scopeId2));
                  } else {
                    _push3(`<div class="w-full h-32 flex justify-center items-center bg-gray-1"${_scopeId2}>`);
                    _push3(ssrRenderComponent(_component_v_progress_circular, {
                      size: 70,
                      width: 7,
                      color: "black",
                      indeterminate: ""
                    }, null, _parent3, _scopeId2));
                    _push3(`</div>`);
                  }
                } else {
                  return [
                    match_detail.value !== null ? (openBlock(), createBlock(_sfc_main$2, {
                      key: 0,
                      summoner_match: _ctx.summoner_match,
                      match_detail: match_detail.value
                    }, null, 8, ["summoner_match", "match_detail"])) : (openBlock(), createBlock("div", {
                      key: 1,
                      class: "w-full h-32 flex justify-center items-center bg-gray-1"
                    }, [
                      createVNode(_component_v_progress_circular, {
                        size: 70,
                        width: 7,
                        color: "black",
                        indeterminate: ""
                      })
                    ]))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_component_v_window_item, { value: "overview" }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$8, {
                    summoner_encounter_count: _ctx.summoner_encounter_count,
                    duration_minutes: unref(getDurationMinutes)(_ctx.summoner_match.match.match_duration),
                    won: _ctx.summoner_match.won,
                    participants: _ctx.summoner_match.match.participants
                  }, null, 8, ["summoner_encounter_count", "duration_minutes", "won", "participants"]),
                  createVNode("div", { class: "flex justify-center flex-col my-4" }, [
                    createVNode(_sfc_main$7, {
                      class: "mx-auto w-2/3",
                      key_stat: "kills",
                      participants: _ctx.summoner_match.match.participants,
                      description: "Team kill",
                      has_won: _ctx.summoner_match.won
                    }, null, 8, ["participants", "has_won"]),
                    createVNode(_sfc_main$7, {
                      class: "mx-auto w-2/3 mt-2",
                      key_stat: "gold_earned",
                      participants: _ctx.summoner_match.match.participants,
                      description: "Team Gold",
                      has_won: _ctx.summoner_match.won
                    }, null, 8, ["participants", "has_won"])
                  ]),
                  createVNode(_sfc_main$8, {
                    summoner_encounter_count: _ctx.summoner_encounter_count,
                    duration_minutes: unref(getDurationMinutes)(_ctx.summoner_match.match.match_duration),
                    won: !_ctx.summoner_match.won,
                    participants: _ctx.summoner_match.match.participants
                  }, null, 8, ["summoner_encounter_count", "duration_minutes", "won", "participants"])
                ]),
                _: 1
              }),
              createVNode(_component_v_window_item, { value: "team_analysis" }, {
                default: withCtx(() => [
                  createVNode("div", { class: "flex justify-center" }, [
                    createVNode("div", { class: "flex items-center justify-center" }, [
                      createVNode("div", { class: "w-4 h-4 rounded-full bg-[#64B5F6] mr-3" }),
                      createVNode("div", null, " Winning team ")
                    ]),
                    createVNode("div", { class: "ml-10 flex items-center justify-center" }, [
                      createVNode("div", { class: "w-4 h-4 rounded-full bg-[#E57373] mr-3" }),
                      createVNode("div", null, " Losing team ")
                    ])
                  ]),
                  createVNode("div", { class: "grid grid-cols-2 gap-4" }, [
                    createVNode(_sfc_main$6, {
                      description: "Champion Kill",
                      split_number: false,
                      participants: _ctx.summoner_match.match.participants,
                      key_stat: "kills"
                    }, null, 8, ["participants"]),
                    createVNode(_sfc_main$6, {
                      description: "Gold Earned",
                      split_number: true,
                      participants: _ctx.summoner_match.match.participants,
                      key_stat: "gold_earned"
                    }, null, 8, ["participants"]),
                    createVNode(_sfc_main$6, {
                      description: "Damage Dealt to Champions",
                      split_number: true,
                      participants: _ctx.summoner_match.match.participants,
                      key_stat: "total_damage_dealt_to_champions"
                    }, null, 8, ["participants"]),
                    createVNode(_sfc_main$6, {
                      description: "Wards Placed",
                      split_number: false,
                      participants: _ctx.summoner_match.match.participants,
                      key_stat: "wards_placed"
                    }, null, 8, ["participants"]),
                    createVNode(_sfc_main$6, {
                      description: "Damage Taken",
                      split_number: true,
                      participants: _ctx.summoner_match.match.participants,
                      key_stat: "total_damage_taken"
                    }, null, 8, ["participants"]),
                    createVNode(_sfc_main$6, {
                      description: "CS",
                      split_number: false,
                      participants: _ctx.summoner_match.match.participants,
                      key_stat: "minions_killed"
                    }, null, 8, ["participants"])
                  ])
                ]),
                _: 1
              }),
              createVNode(_component_v_window_item, { value: "build" }, {
                default: withCtx(() => [
                  match_detail.value !== null ? (openBlock(), createBlock(_sfc_main$2, {
                    key: 0,
                    summoner_match: _ctx.summoner_match,
                    match_detail: match_detail.value
                  }, null, 8, ["summoner_match", "match_detail"])) : (openBlock(), createBlock("div", {
                    key: 1,
                    class: "w-full h-32 flex justify-center items-center bg-gray-1"
                  }, [
                    createVNode(_component_v_progress_circular, {
                      size: 70,
                      width: 7,
                      color: "black",
                      indeterminate: ""
                    })
                  ]))
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
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchDetail.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const getLeagueRankFromInt = (league_rank) => {
  if (league_rank === 3)
    return "I";
  if (league_rank === 2)
    return "II";
  if (league_rank === 1)
    return "III";
  if (league_rank === 0)
    return "IV";
  return "I";
};
const getAvgRankString = (summoner_matches) => {
  let total_team_position = 0;
  let total_team_count = 0;
  summoner_matches.forEach((match) => {
    if (match.summoner.solo_q) {
      total_team_position += match.summoner.solo_q.overall_position;
      total_team_count++;
    }
  });
  if (total_team_position === 0) {
    return "Undefined";
  }
  let avg_position = Math.ceil(total_team_position / (total_team_count ? total_team_count : 1));
  return getTierRankString(avg_position);
};
const getTierRankString = (avg_overall_position) => {
  let tier;
  let rank;
  if (avg_overall_position >= 30) {
    tier = "CHALLENGER";
    rank = "I";
  } else if (avg_overall_position >= 29) {
    tier = "GRANDMASTER";
    rank = "I";
  } else if (avg_overall_position >= 28) {
    tier = "MASTER";
    rank = "I";
  } else {
    if (avg_overall_position < 4) {
      tier = "IRON";
      rank = getLeagueRankFromInt(avg_overall_position);
    } else if (avg_overall_position < 8) {
      tier = "BRONZE";
      rank = getLeagueRankFromInt(avg_overall_position - 4);
    } else if (avg_overall_position < 12) {
      tier = "SILVER";
      rank = getLeagueRankFromInt(avg_overall_position - 8);
    } else if (avg_overall_position < 16) {
      tier = "GOLD";
      rank = getLeagueRankFromInt(avg_overall_position - 12);
    } else if (avg_overall_position < 20) {
      tier = "PLATINUM";
      rank = getLeagueRankFromInt(avg_overall_position - 16);
    } else if (avg_overall_position < 24) {
      tier = "EMERALD";
      rank = getLeagueRankFromInt(avg_overall_position - 20);
    } else {
      tier = "DIAMOND";
      rank = getLeagueRankFromInt(avg_overall_position - 24);
    }
  }
  return `${tier} ${rank}`;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "MatchesRow",
  __ssrInlineRender: true,
  props: {
    summoner_match: {},
    summoner_encounter_count: {},
    auto_open: { type: Boolean }
  },
  setup(__props) {
    const props = __props;
    onMounted(() => {
      if (props.auto_open) {
        toggleIsOpen();
      }
    });
    let is_open = ref(false);
    const summoner = getSummoner();
    const loaded_summoner_match = ref(null);
    const toggleIsOpen = () => {
      is_open.value = !is_open.value;
      if (is_open.value && !loaded_summoner_match.value) {
        axios.get(route("summoner.match.loaded", {
          summoner_match_id: props.summoner_match.id,
          summoner_id: getSummoner().id
        })).then((response) => {
          loaded_summoner_match.value = response.data.summoner_match;
        }).catch((error) => {
        });
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h;
      const _component_VImg = resolveComponent("VImg");
      const _component_VIcon = resolveComponent("VIcon");
      const _component_v_progress_circular = resolveComponent("v-progress-circular");
      _push(`<!--[--><div class="${ssrRenderClass(`${_ctx.summoner_match.won ? "bg-blue-1" : "bg-red-1"}  my-1.5 flex rounded opacity-95 text-gray-200`)}"><div class="${ssrRenderClass(`${_ctx.summoner_match.won ? "bg-blue-2" : "bg-red-2"} w-3 rounded-l`)}"></div><div class="w-32 flex flex-col justify-center space-y-1 my-2 pl-4"><div>${ssrInterpolate((_b = (_a = _ctx.summoner_match.match) == null ? void 0 : _a.queue) == null ? void 0 : _b.description.replace("games", ""))}</div><div>${ssrInterpolate(unref(moment)((_c = _ctx.summoner_match.match) == null ? void 0 : _c.match_end).fromNow())}</div><div>${ssrInterpolate(_ctx.summoner_match.won ? "Victory" : "Defeat")}</div><div>${ssrInterpolate((_d = _ctx.summoner_match.match) == null ? void 0 : _d.match_duration)}</div></div><div class="ml-4 w-64 flex flex-col justify-center my-2"><div class="w-full flex"><div class="w-16 h-16 relative">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlChampionHelper)((_e = _ctx.summoner_match.champion) == null ? void 0 : _e.img_url),
        class: "w-16 h-16 rounded-full"
      }, null, _parent));
      _push(`<div class="absolute -bottom-1 right-0 bg-gray-1 rounded-full px-0.5">${ssrInterpolate(_ctx.summoner_match.champ_level)}</div></div><div class="flex flex-col ml-1">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlSummonerSpellHelper)(_ctx.summoner_match.summoner_spell1.img_url),
        class: "w-7 h-7 rounded"
      }, null, _parent));
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlSummonerSpellHelper)(_ctx.summoner_match.summoner_spell2.img_url),
        class: "w-7 h-7 rounded mt-1"
      }, null, _parent));
      _push(`</div><div class="flex flex-col ml-1">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.summoner_match.perks.primary_selection.img_url),
        class: "w-7 h-7 bg-black rounded-full"
      }, null, _parent));
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlPerkHelper)(_ctx.summoner_match.perks.sub_style.img_url),
        class: "w-7 h-7 mt-1"
      }, null, _parent));
      _push(`</div><div class="ml-4 text-xl flex justify-center items-center flex-col"><div class="flex"><div class="text-gray-5 font-bold">${ssrInterpolate(_ctx.summoner_match.kills)}</div><div class="text-gray-4 mx-1">/</div><div class="text-red-4 font-bold">${ssrInterpolate(_ctx.summoner_match.deaths)}</div><div class="text-gray-4 mx-1">/</div><div class="text-gray-5 font-bold">${ssrInterpolate(_ctx.summoner_match.assists)}</div></div><div>${ssrInterpolate((_f = _ctx.summoner_match.kda) == null ? void 0 : _f.toFixed(1))}:1 KDA </div></div></div><div class="flex mt-4 space-x-2 my-2"><!--[-->`);
      ssrRenderList(_ctx.summoner_match.items, (item) => {
        _push(`<div class="w-8 h-8">`);
        _push(ssrRenderComponent(_component_VImg, {
          src: unref(urlItemHelper)(item.img_url),
          class: "w-8 h-8"
        }, null, _parent));
        _push(`</div>`);
      });
      _push(`<!--]--></div></div><div class="w-48 flex flex-col justify-center space-y-1 my-2 ml-6"><div>P/Kill ${ssrInterpolate((((_g = _ctx.summoner_match) == null ? void 0 : _g.kill_participation) * 100).toFixed(0))}%</div><div>Control Ward nc</div><div>CS ${ssrInterpolate(_ctx.summoner_match.minions_killed)} (${ssrInterpolate((_ctx.summoner_match.minions_killed / unref(getDurationMinutes)((_h = _ctx.summoner_match.match) == null ? void 0 : _h.match_duration)).toFixed(1))}) </div><div>${ssrInterpolate(unref(getAvgRankString)(_ctx.summoner_match.match.participants))}</div></div><div class="flex w-96 justify-self-end my-2"><div class="grid grid-cols-2"><div><!--[-->`);
      ssrRenderList(_ctx.summoner_match.match.participants, (participant) => {
        _push(`<!--[-->`);
        if (_ctx.summoner_match.won == participant.won) {
          _push(ssrRenderComponent(_sfc_main$9, {
            key: participant.id,
            participant,
            summoner_encounter_count: _ctx.summoner_encounter_count,
            is_self: _ctx.summoner_match.summoner_id === participant.summoner_id
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div><div><!--[-->`);
      ssrRenderList(_ctx.summoner_match.match.participants, (participant) => {
        _push(`<!--[-->`);
        if (_ctx.summoner_match.won != participant.won) {
          _push(ssrRenderComponent(_sfc_main$9, {
            key: participant.id,
            participant,
            summoner_encounter_count: _ctx.summoner_encounter_count,
            is_self: _ctx.summoner_match.summoner_id === participant.summoner_id
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div></div></div><div class="flex items-center mx-1">`);
      if (!_ctx.auto_open) {
        _push(ssrRenderComponent(PrimaryButton, {
          onClick: ($event) => unref(navigateToMatch)(unref(summoner).id, _ctx.summoner_match.id),
          class: "w-full h-8 text-sm",
          color: "white"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Go `);
            } else {
              return [
                createTextVNode("Go ")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<div class="w-14"></div>`);
      }
      _push(`</div><div class="flex justify-end ml-1 w-14"><div class="${ssrRenderClass(`${_ctx.summoner_match.won ? "bg-blue-2 text-blue-4" : "bg-red-2 text-red-4"} w-14 flex items-end pb-4 rounded-r`)}">`);
      _push(ssrRenderComponent(_component_VIcon, {
        onClick: toggleIsOpen,
        class: `${unref(is_open) ? "transform rotate-180" : ""} cursor-pointer w-8 h-8 mx-auto`,
        icon: "fa fa-chevron-down"
      }, null, _parent));
      _push(`</div></div></div>`);
      if (unref(is_open) && loaded_summoner_match.value) {
        _push(ssrRenderComponent(_sfc_main$1, {
          summoner_match: loaded_summoner_match.value,
          summoner_encounter_count: _ctx.summoner_encounter_count
        }, null, _parent));
      } else if (unref(is_open)) {
        _push(`<div class="w-full h-32 flex justify-center items-center bg-gray-1">`);
        _push(ssrRenderComponent(_component_v_progress_circular, {
          size: 70,
          width: 7,
          color: "black",
          indeterminate: ""
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/MatchesRow.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
