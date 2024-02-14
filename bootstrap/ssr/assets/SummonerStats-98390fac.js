import { defineComponent, resolveComponent, mergeProps, unref, withCtx, createVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderClass, ssrInterpolate, ssrRenderAttr, ssrRenderComponent } from "vue/server-renderer";
import { w as withoutTagLine, a as urlProPlayerHelper, e as urlProfilIconHelper } from "./SummonerHeader-7f8813f6.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "SummonerStats",
  __ssrInlineRender: true,
  props: {
    summoner_stats: {
      type: Object,
      required: true
    },
    summoner: {
      type: Object,
      required: true
    },
    color: {
      type: String,
      default: "blue",
      required: false
    },
    is_reverse: {
      type: Boolean,
      default: false,
      required: false
    },
    justify: {
      type: String,
      default: "end",
      required: false
    },
    with_summoner_name: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      const _component_v_tooltip = resolveComponent("v-tooltip");
      const _component_VImg = resolveComponent("VImg");
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: `text-gray-5 bg-gray-1 text-gray-1 flex justify-${__props.justify} rounded my-3  p-4 ${__props.is_reverse ? "flex-row-reverse " : ""}`
      }, _attrs))}>`);
      if (__props.with_summoner_name) {
        _push(`<div class="${ssrRenderClass(`mx-4 flex items-center ${!__props.is_reverse ? "flex-row-reverse " : ""}`)}"><div><div class="text-xl font-bold cursor-pointer mx-2 flex flex-col">${ssrInterpolate(unref(withoutTagLine)(__props.summoner.name))}</div>`);
        if (__props.summoner.pro_player) {
          _push(`<div class="flex ml-1 justify-center text-xs"><a${ssrRenderAttr("href", unref(urlProPlayerHelper)(__props.summoner.pro_player.slug))}><div class="bg-purple-800 py-0.5 px-1 rounded"> PRO `);
          _push(ssrRenderComponent(_component_v_tooltip, {
            activator: "parent",
            location: "bottom",
            class: "text-center"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              var _a2, _b, _c, _d;
              if (_push2) {
                _push2(`<p${_scopeId}>${ssrInterpolate((_a2 = __props.summoner.pro_player) == null ? void 0 : _a2.team_name)}</p><p${_scopeId}>${ssrInterpolate((_b = __props.summoner.pro_player) == null ? void 0 : _b.name)}</p>`);
              } else {
                return [
                  createVNode("p", null, toDisplayString((_c = __props.summoner.pro_player) == null ? void 0 : _c.team_name), 1),
                  createVNode("p", null, toDisplayString((_d = __props.summoner.pro_player) == null ? void 0 : _d.name), 1)
                ];
              }
            }),
            _: 1
          }, _parent));
          _push(`</div></a></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
        _push(ssrRenderComponent(_component_VImg, {
          src: unref(urlProfilIconHelper)(__props.summoner.profile_icon_id),
          class: "w-16 h-16 rounded-full mx-1"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="mx-4 flex flex-col items-center justify-center"><div class="flex space-x-2"><div>${ssrInterpolate(__props.summoner_stats.total_game)}G</div><div>${ssrInterpolate(__props.summoner_stats.total_win)}W</div><div>${ssrInterpolate(__props.summoner_stats.total_game - __props.summoner_stats.total_win)}L</div></div><div>${ssrInterpolate(__props.summoner_stats.total_game === 0 ? 0 : (__props.summoner_stats.total_win / __props.summoner_stats.total_game * 100).toFixed(0))}% Win Rate </div></div><div class="mx-4 flex flex-col items-center justify-center"><div class="flex"><div class="text-gray-5 font-bold">${ssrInterpolate(__props.summoner_stats.avg_kills.toFixed(1))}</div><div class="text-gray-4 mx-1">/</div><div class="text-red-3 font-bold">${ssrInterpolate(__props.summoner_stats.avg_deaths.toFixed(1))}</div><div class="text-gray-4 mx-1">/</div><div class="text-gray-5 font-bold">${ssrInterpolate(__props.summoner_stats.avg_assists.toFixed(1))}</div></div><div class="font-bold text-xl">${ssrInterpolate((_a = __props.summoner_stats.avg_kda) == null ? void 0 : _a.toFixed(2))}:1 KDA </div><div> P/Kill ${ssrInterpolate((__props.summoner_stats.avg_kill_participation * 100).toFixed(0))}% </div></div></div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/SummonerStats.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
