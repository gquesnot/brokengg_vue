import { resolveComponent, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { b as urlProfilIconHelper } from "./url_helpers-8bee5ae0.js";
import { usePage } from "@inertiajs/vue3";
const _sfc_main = {
  __name: "SummonerStats",
  __ssrInlineRender: true,
  props: {
    /** @type {SummonerInterface} */
    summoner: {
      type: SummonerInterface,
      required: true
    },
    /** @type {SummonerStatsInterface} */
    summoner_stats: {
      type: SummonerStatsInterface,
      required: true
    },
    color: {
      type: String,
      default: "bg-blue-500",
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
    const version = usePage().props.version;
    usePage().props.route_params;
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      const _component_VImg = resolveComponent("VImg");
      if (__props.summoner_stats !== null) {
        _push(`<div${ssrRenderAttrs(mergeProps({
          class: `${__props.color} flex justify-${__props.justify}  my-2  p-4 ${__props.is_reverse ? "flex-row-reverse " : ""}`
        }, _attrs))}>`);
        if (__props.with_summoner_name) {
          _push(`<div class="${ssrRenderClass(`mx-4 flex items-center ${__props.is_reverse ? "flex-row-reverse " : ""}`)}">`);
          _push(ssrRenderComponent(_component_VImg, {
            src: unref(urlProfilIconHelper)(unref(version), __props.summoner.profile_icon_id),
            class: "w-16 h-16 rounded-full"
          }, null, _parent));
          _push(`<div class="text-xl font-bold cursor-pointer mx-4">${ssrInterpolate(__props.summoner.name)}</div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<div class="mx-4 flex flex-col items-center justify-center"><div class="flex space-x-2"><div>${ssrInterpolate(__props.summoner_stats.total_game)}G</div><div>${ssrInterpolate(__props.summoner_stats.total_win)}W</div><div>${ssrInterpolate(__props.summoner_stats.total_game - __props.summoner_stats.total_win)}L</div></div><div>${ssrInterpolate(__props.summoner_stats.total_game === 0 ? 0 : (__props.summoner_stats.total_win / __props.summoner_stats.total_game * 100).toFixed(0))}% Win Rate </div></div><div class="mx-4 flex flex-col items-center justify-center"><div class="flex"><div class="text-white font-bold">${ssrInterpolate(__props.summoner_stats.avg_kills.toFixed(1))}</div><div class="text-white mx-1">/</div><div class="text-red-900 font-bold">${ssrInterpolate(__props.summoner_stats.avg_deaths.toFixed(1))}</div><div class="text-white mx-1">/</div><div class="text-white font-bold">${ssrInterpolate(__props.summoner_stats.avg_assists.toFixed(1))}</div></div><div class="font-bold text-2xl">${ssrInterpolate((_a = __props.summoner_stats.avg_kda) == null ? void 0 : _a.toFixed(2))}:1 KDA </div><div> P/Kill ${ssrInterpolate((__props.summoner_stats.avg_kill_participation * 100).toFixed(0))}% </div></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/SummonerStats.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
