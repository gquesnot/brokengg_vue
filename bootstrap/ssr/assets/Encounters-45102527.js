import { defineComponent, resolveComponent, unref, withCtx, createVNode, toDisplayString, createTextVNode, useSSRContext, ref, watch, mergeProps, isRef, openBlock, createBlock, Fragment, renderList } from "vue";
import { ssrInterpolate, ssrRenderAttr, ssrRenderComponent, ssrRenderAttrs, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { debounce } from "lodash";
import { g as getSummoner, w as withoutTagLine, a as urlProPlayerHelper, b as getParamsWithFilters, c as getFilters, d as getOnly, _ as _sfc_main$2 } from "./SummonerHeader-7f8813f6.js";
import { router } from "@inertiajs/vue3";
import { _ as _sfc_main$3 } from "./TextInput-107f91f9.js";
import { _ as _sfc_main$4 } from "./Pagination-ff514cc5.js";
import { P as PrimaryButton } from "./PrimaryButton-cbcf38ef.js";
import { n as navigateToEncounter } from "./router_helpers-0edbd391.js";
import "vue3-datepicker";
import "./ResponsiveNavLink-d4a8b54d.js";
import "moment";
import "./AlertApi-748a9f42.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "EncountersRow",
  __ssrInlineRender: true,
  props: {
    encounter: {}
  },
  setup(__props) {
    const summoner = getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      const _component_v_tooltip = resolveComponent("v-tooltip");
      _push(`<!--[--><td>${ssrInterpolate(unref(withoutTagLine)(_ctx.encounter.name))}</td><td><div class="flex items-center"><div>${ssrInterpolate(_ctx.encounter.encounter_count)}</div>`);
      if (_ctx.encounter.summoner.pro_player) {
        _push(`<div class="flex ml-1 justify-center text-xs ml-2"><a${ssrRenderAttr("href", unref(urlProPlayerHelper)(_ctx.encounter.summoner.pro_player.slug))}><div class="bg-purple-800 py-0.5 px-1 rounded"> PRO `);
        _push(ssrRenderComponent(_component_v_tooltip, {
          activator: "parent",
          location: "bottom",
          class: "text-center"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            var _a, _b, _c, _d;
            if (_push2) {
              _push2(`<p${_scopeId}>${ssrInterpolate((_a = _ctx.encounter.summoner.pro_player) == null ? void 0 : _a.team_name)}</p><p${_scopeId}>${ssrInterpolate((_b = _ctx.encounter.summoner.pro_player) == null ? void 0 : _b.name)}</p>`);
            } else {
              return [
                createVNode("p", null, toDisplayString((_c = _ctx.encounter.summoner.pro_player) == null ? void 0 : _c.team_name), 1),
                createVNode("p", null, toDisplayString((_d = _ctx.encounter.summoner.pro_player) == null ? void 0 : _d.name), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div></a></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></td><td>`);
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: ($event) => unref(navigateToEncounter)(unref(summoner).id, _ctx.encounter.summoner_id)
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Go `);
          } else {
            return [
              createTextVNode(" Go ")
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/EncountersRow.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Encounters",
  __ssrInlineRender: true,
  props: {
    search: {},
    encounters: {}
  },
  setup(__props) {
    const props = __props;
    let search = ref(props.search ?? "");
    const summoner = getSummoner();
    watch(search, debounce(function(value) {
      router.visit(route("summoner.encounters", getParamsWithFilters(getFilters(), {
        summoner_id: summoner.id.toString(),
        search: value
      })), {
        preserveState: true,
        preserveScroll: true,
        only: getOnly()
      });
    }, 500));
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTable = resolveComponent("VTable");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6 text-gray-5" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$2, { tab: "Encounters" }, null, _parent));
      _push(`<div>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        modelValue: unref(search),
        "onUpdate:modelValue": ($event) => isRef(search) ? search.value = $event : search = $event,
        placeholder: "Search Summoner",
        class: "p-2 my-3 w-25 border-1 border border-gray-200"
      }, null, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(_component_VTable, null, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<thead${_scopeId}><tr${_scopeId}><th class="text-left"${_scopeId}>Summoner</th><th class="text-left"${_scopeId}>Count</th><th class="text-left"${_scopeId}></th></tr></thead><tbody${_scopeId}>`);
            if (_ctx.encounters.data.length === 0) {
              _push2(`<tr${_scopeId}><td colspan="3" class="text-center"${_scopeId}>No Encounters Found</td></tr>`);
            } else {
              _push2(`<!--[-->`);
              ssrRenderList(_ctx.encounters.data, (encounter, idx) => {
                _push2(`<tr class="${ssrRenderClass((idx % 2 === 0 ? "bg-zinc-800" : "") + " hover:bg-zinc-900")}"${_scopeId}>`);
                _push2(ssrRenderComponent(_sfc_main$1, {
                  key: encounter.summoner_id,
                  encounter
                }, null, _parent2, _scopeId));
                _push2(`</tr>`);
              });
              _push2(`<!--]-->`);
            }
            _push2(`</tbody>`);
          } else {
            return [
              createVNode("thead", null, [
                createVNode("tr", null, [
                  createVNode("th", { class: "text-left" }, "Summoner"),
                  createVNode("th", { class: "text-left" }, "Count"),
                  createVNode("th", { class: "text-left" })
                ])
              ]),
              createVNode("tbody", null, [
                _ctx.encounters.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                  createVNode("td", {
                    colspan: "3",
                    class: "text-center"
                  }, "No Encounters Found")
                ])) : (openBlock(true), createBlock(Fragment, { key: 1 }, renderList(_ctx.encounters.data, (encounter, idx) => {
                  return openBlock(), createBlock("tr", {
                    key: encounter.summoner_id,
                    class: (idx % 2 === 0 ? "bg-zinc-800" : "") + " hover:bg-zinc-900"
                  }, [
                    (openBlock(), createBlock(_sfc_main$1, {
                      key: encounter.summoner_id,
                      encounter
                    }, null, 8, ["encounter"]))
                  ], 2);
                }), 128))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$4, {
        links: _ctx.encounters.links
      }, null, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Encounters.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
