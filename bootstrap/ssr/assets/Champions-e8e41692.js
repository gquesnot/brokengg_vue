import { computed, resolveComponent, unref, withCtx, createTextVNode, createVNode, useSSRContext, mergeProps, openBlock, createBlock, Fragment, renderList } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttrs, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { _ as _sfc_main$2 } from "./SummonerHeader-0b1a75fc.js";
import { usePage, Link, router } from "@inertiajs/vue3";
import { u as urlChampionHelper } from "./url_helpers-8bee5ae0.js";
import { P as PrimaryButton } from "./PrimaryButton-d82933f3.js";
import { _ as _sfc_main$3 } from "./Pagination-e3f93c8f.js";
import "./InputError-be673cc6.js";
import "./ResponsiveNavLink-2d39c768.js";
import "vue3-datepicker";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$1 = {
  __name: "ChampionsRow",
  __ssrInlineRender: true,
  props: {
    champion: {
      type: Object
    }
  },
  setup(__props) {
    const props = __props;
    const version = usePage().props.version;
    const route_params = usePage().props.route_params;
    const winRate = computed(() => (props.champion.wins / props.champion.total * 100).toFixed(0));
    const loses = computed(() => props.champion.total - props.champion.wins);
    const navigateToChampion = () => {
      let base_params = {
        ...route_params,
        champion: props.champion.champion_id
      };
      router.visit(route("summoner.champion", base_params), {
        preserveState: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      const _component_VImg = resolveComponent("VImg");
      _push(`<!--[--><td><div class="flex items-center w-50">`);
      _push(ssrRenderComponent(_component_VImg, {
        src: unref(urlChampionHelper)(unref(version), (_a = props.champion.champion) == null ? void 0 : _a.img_url),
        class: "h-12 w-12 rounded"
      }, null, _parent));
      _push(`<div class="ml-2">${ssrInterpolate(props.champion.champion.name)}</div></div></td><td><div><div>${ssrInterpolate(props.champion.wins)}W</div><div>${ssrInterpolate(loses.value)}L</div></div></td><td>${ssrInterpolate(winRate.value)}% </td><td>${ssrInterpolate(props.champion.avg_kills)}</td><td>${ssrInterpolate(props.champion.avg_deaths)}</td><td>${ssrInterpolate(props.champion.avg_assists)}</td><td>${ssrInterpolate(((props.champion.avg_kills + props.champion.avg_assists) / props.champion.avg_deaths).toFixed(2))}:1</td><td>${ssrInterpolate(props.champion.max_kills)}</td><td>${ssrInterpolate(props.champion.max_deaths)}</td><td>${ssrInterpolate(props.champion.max_assists)}</td><td>${ssrInterpolate(props.champion.avg_cs)}</td><td>${ssrInterpolate((props.champion.avg_damage_dealt_to_champions / 1e3).toFixed(1))}k</td><td>${ssrInterpolate((props.champion.avg_damage_taken / 1e3).toFixed(1))}k</td><td>${ssrInterpolate((props.champion.avg_gold / 1e3).toFixed(1))}k</td><td>${ssrInterpolate(props.champion.total_double_kills)}</td><td>${ssrInterpolate(props.champion.total_triple_kills)}</td><td>${ssrInterpolate(props.champion.total_quadra_kills)}</td><td>${ssrInterpolate(props.champion.total_penta_kills)}</td><td>`);
      _push(ssrRenderComponent(PrimaryButton, { onClick: navigateToChampion }, {
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
      _push(`</td><!--]-->`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/ChampionsRow.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Champions",
  __ssrInlineRender: true,
  props: {
    champions: {
      type: Array
    }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTable = resolveComponent("VTable");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$2, { tab: "Champions" }, null, _parent));
      _push(ssrRenderComponent(_component_VTable, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<thead${_scopeId}><tr${_scopeId}><th class="text-left"${_scopeId}>Champion</th><th class="text-left"${_scopeId}>W-L</th><th class="text-left"${_scopeId}>Win Rate</th><th class="text-left"${_scopeId}>Avg Kills</th><th class="text-left"${_scopeId}>Avg Deaths</th><th class="text-left"${_scopeId}>Avg Assists</th><th class="text-left"${_scopeId}>Avg kda</th><th class="text-left"${_scopeId}>Max Kills</th><th class="text-left"${_scopeId}>Max Deaths</th><th class="text-left"${_scopeId}>Max Assists</th><th class="text-left"${_scopeId}>Avg CS</th><th class="text-left"${_scopeId}>Avg Damage Dealt to Champions</th><th class="text-left"${_scopeId}>Avg Damage Taken</th><th class="text-left"${_scopeId}>Avg Gold</th><th class="text-left"${_scopeId}>Total Double Kills</th><th class="text-left"${_scopeId}>Total Triple Kills</th><th class="text-left"${_scopeId}>Total Quadra Kills</th><th class="text-left"${_scopeId}>Total Penta Kills</th><th${_scopeId}></th></tr></thead><tbody${_scopeId}><!--[-->`);
            ssrRenderList(__props.champions.data, (champion, idx) => {
              _push2(`<tr class="${ssrRenderClass((idx % 2 === 0 ? "bg-gray-200" : "") + " hover:bg-gray-300")}"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                champion,
                key: champion.id
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
                  createVNode("th", { class: "text-left" }, "Avg Kills"),
                  createVNode("th", { class: "text-left" }, "Avg Deaths"),
                  createVNode("th", { class: "text-left" }, "Avg Assists"),
                  createVNode("th", { class: "text-left" }, "Avg kda"),
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
                (openBlock(true), createBlock(Fragment, null, renderList(__props.champions.data, (champion, idx) => {
                  return openBlock(), createBlock("tr", {
                    key: champion.id,
                    class: (idx % 2 === 0 ? "bg-gray-200" : "") + " hover:bg-gray-300"
                  }, [
                    (openBlock(), createBlock(_sfc_main$1, {
                      champion,
                      key: champion.id
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
        links: __props.champions.links
      }, null, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Champions.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
