import { defineComponent, computed, resolveComponent, unref, withCtx, createTextVNode, useSSRContext, mergeProps, createVNode, openBlock, createBlock, Fragment, renderList } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttrs, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { u as urlChampionHelper, g as getSummoner, _ as _sfc_main$2 } from "./SummonerHeader-7f8813f6.js";
import { _ as _sfc_main$3 } from "./Pagination-ff514cc5.js";
import { router } from "@inertiajs/vue3";
import { P as PrimaryButton } from "./PrimaryButton-cbcf38ef.js";
import "vue3-datepicker";
import "./ResponsiveNavLink-d4a8b54d.js";
import "moment";
import "./AlertApi-748a9f42.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "ChampionsRow",
  __ssrInlineRender: true,
  props: {
    champion: {},
    version: {}
  },
  setup(__props) {
    const props = __props;
    const winRate = computed(() => (props.champion.total_win / props.champion.total * 100).toFixed(0));
    const loses = computed(() => props.champion.total - props.champion.total_win);
    const navigateToChampion = () => {
      router.visit(route("summoner.champion", {
        summoner_id: getSummoner().id,
        champion_id: props.champion.champion_id
      }), {
        preserveState: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VImg = resolveComponent("VImg");
      _push(`<!--[--><td><div class="flex items-center w-50">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlChampionHelper)(props.champion.champion.img_url),
        class: "h-12 w-12 rounded"
      }, null, _parent));
      _push(`<div class="ml-2">${ssrInterpolate(props.champion.champion.name)}</div></div></td><td><div><div>${ssrInterpolate(props.champion.total_win)}W</div><div>${ssrInterpolate(loses.value)}L</div></div></td><td>${ssrInterpolate(winRate.value)}% </td><td><div class="flex flex-col w-36 items-center"><div class="flex">${ssrInterpolate(props.champion.avg_kills)} / ${ssrInterpolate(props.champion.avg_deaths)} / ${ssrInterpolate(props.champion.avg_assists)}</div><div>${ssrInterpolate(((props.champion.avg_kills + props.champion.avg_assists) / props.champion.avg_deaths).toFixed(2))}:1 </div></div></td><td>${ssrInterpolate(props.champion.max_kills)}</td><td>${ssrInterpolate(props.champion.max_deaths)}</td><td>${ssrInterpolate(props.champion.max_assists)}</td><td>${ssrInterpolate(props.champion.avg_cs)}</td><td>${ssrInterpolate((props.champion.avg_damage_dealt_to_champions / 1e3).toFixed(1))}k</td><td>${ssrInterpolate((props.champion.avg_damage_taken / 1e3).toFixed(1))}k</td><td>${ssrInterpolate((props.champion.avg_gold / 1e3).toFixed(1))}k</td><td>${ssrInterpolate(props.champion.total_double_kills)}</td><td>${ssrInterpolate(props.champion.total_triple_kills)}</td><td>${ssrInterpolate(props.champion.total_quadra_kills)}</td><td>${ssrInterpolate(props.champion.total_penta_kills)}</td><td>`);
      _push(ssrRenderComponent(PrimaryButton, { onClick: navigateToChampion }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Go`);
          } else {
            return [
              createTextVNode("Go")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</td><!--]-->`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/ChampionsRow.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Champions",
  __ssrInlineRender: true,
  props: {
    champions: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTable = resolveComponent("VTable");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6 text-gray-5" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$2, { tab: "Champions" }, null, _parent));
      _push(`<div class="position-absolute w-10/12 left-40 my-4">`);
      _push(ssrRenderComponent(_component_VTable, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<thead${_scopeId}><tr${_scopeId}><th class="text-left"${_scopeId}>Champion</th><th class="text-left"${_scopeId}>W-L</th><th class="text-left"${_scopeId}>Win Rate</th><th class="text-left"${_scopeId}>Avg KDA</th><th class="text-left"${_scopeId}>Max Kills</th><th class="text-left"${_scopeId}>Max Deaths</th><th class="text-left"${_scopeId}>Max Assists</th><th class="text-left"${_scopeId}>Avg CS</th><th class="text-left"${_scopeId}>Avg Damage Dealt to Champions</th><th class="text-left"${_scopeId}>Avg Damage Taken</th><th class="text-left"${_scopeId}>Avg Gold</th><th class="text-left"${_scopeId}>Total Double Kills</th><th class="text-left"${_scopeId}>Total Triple Kills</th><th class="text-left"${_scopeId}>Total Quadra Kills</th><th class="text-left"${_scopeId}>Total Penta Kills</th><th${_scopeId}></th></tr></thead><tbody${_scopeId}><!--[-->`);
            ssrRenderList(_ctx.champions.data, (champion, idx) => {
              _push2(`<tr class="${ssrRenderClass((idx % 2 === 0 ? "bg-zinc-800" : "") + " hover:bg-zinc-900")}"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                champion,
                key: champion.id,
                version: ""
              }, null, _parent2, _scopeId));
              _push2(`</tr>`);
            });
            _push2(`<!--]--></tbody>`);
          } else {
            return [
              createVNode("thead", null, [
                createVNode("tr", null, [
                  createVNode("th", { class: "text-left" }, "Champion"),
                  createVNode("th", { class: "text-left" }, "W-L"),
                  createVNode("th", { class: "text-left" }, "Win Rate"),
                  createVNode("th", { class: "text-left" }, "Avg KDA"),
                  createVNode("th", { class: "text-left" }, "Max Kills"),
                  createVNode("th", { class: "text-left" }, "Max Deaths"),
                  createVNode("th", { class: "text-left" }, "Max Assists"),
                  createVNode("th", { class: "text-left" }, "Avg CS"),
                  createVNode("th", { class: "text-left" }, "Avg Damage Dealt to Champions"),
                  createVNode("th", { class: "text-left" }, "Avg Damage Taken"),
                  createVNode("th", { class: "text-left" }, "Avg Gold"),
                  createVNode("th", { class: "text-left" }, "Total Double Kills"),
                  createVNode("th", { class: "text-left" }, "Total Triple Kills"),
                  createVNode("th", { class: "text-left" }, "Total Quadra Kills"),
                  createVNode("th", { class: "text-left" }, "Total Penta Kills"),
                  createVNode("th")
                ])
              ]),
              createVNode("tbody", null, [
                (openBlock(true), createBlock(Fragment, null, renderList(_ctx.champions.data, (champion, idx) => {
                  return openBlock(), createBlock("tr", {
                    key: champion.id,
                    class: (idx % 2 === 0 ? "bg-zinc-800" : "") + " hover:bg-zinc-900"
                  }, [
                    (openBlock(), createBlock(_sfc_main$1, {
                      champion,
                      key: champion.id,
                      version: ""
                    }, null, 8, ["champion"]))
                  ], 2);
                }), 128))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$3, {
        links: _ctx.champions.links
      }, null, _parent));
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Champions.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
