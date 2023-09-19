import { unref, withCtx, createTextVNode, createVNode, useSSRContext, ref, watch, resolveComponent, mergeProps, isRef, openBlock, createBlock, Fragment, renderList } from "vue";
import { ssrInterpolate, ssrRenderComponent, ssrRenderAttrs, ssrRenderList, ssrRenderClass } from "vue/server-renderer";
import { Link, usePage, router } from "@inertiajs/vue3";
import { g as getSummoner, n as navigateToEncounter, a as getParams, _ as _sfc_main$2 } from "./SummonerHeader-0b1a75fc.js";
import { _ as _sfc_main$3 } from "./TextInput-ee18b23f.js";
import { P as PrimaryButton } from "./PrimaryButton-d82933f3.js";
import { _ as _sfc_main$4 } from "./Pagination-e3f93c8f.js";
import { debounce } from "lodash";
import "./InputError-be673cc6.js";
import "./ResponsiveNavLink-2d39c768.js";
import "vue3-datepicker";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$1 = {
  __name: "EncountersRow",
  __ssrInlineRender: true,
  props: {
    encounter: {
      type: Object
    }
  },
  setup(__props) {
    const summoner = getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><td>${ssrInterpolate(__props.encounter.name)}</td><td>${ssrInterpolate(__props.encounter.encounter_count)}</td><td>`);
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: ($event) => unref(navigateToEncounter)(unref(summoner).id, __props.encounter.summoner_id)
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
      _push(`</td><!--]-->`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/EncountersRow.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Encounters",
  __ssrInlineRender: true,
  props: {
    search: {
      type: String,
      default: null
    },
    encounters: {
      type: Object,
      required: true
    }
  },
  setup(__props) {
    const props = __props;
    const route_params = usePage().props.route_params;
    const only = usePage().props.only;
    let search = ref(props.search ?? "");
    watch(search, debounce(function(value) {
      if (value === "") {
        value = null;
      }
      let base_params = {
        ...route_params,
        search: value
      };
      router.visit(route("summoner.encounters", getParams(usePage().props.filters, base_params)), {
        preserveState: true,
        preserveScroll: true,
        only
      });
    }, 500));
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTable = resolveComponent("VTable");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
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
            if (__props.encounters.data.length === 0) {
              _push2(`<tr${_scopeId}><td colspan="3" class="text-center"${_scopeId}>No Encounters Found</td></tr>`);
            } else {
              _push2(`<!--[-->`);
              ssrRenderList(__props.encounters.data, (encounter, idx) => {
                _push2(`<tr class="${ssrRenderClass((idx % 2 === 0 ? "bg-gray-200" : "") + " hover:bg-gray-300")}"${_scopeId}>`);
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
                __props.encounters.data.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                  createVNode("td", {
                    colspan: "3",
                    class: "text-center"
                  }, "No Encounters Found")
                ])) : (openBlock(true), createBlock(Fragment, { key: 1 }, renderList(__props.encounters.data, (encounter, idx) => {
                  return openBlock(), createBlock("tr", {
                    key: encounter.summoner_id,
                    class: (idx % 2 === 0 ? "bg-gray-200" : "") + " hover:bg-gray-300"
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
        links: __props.encounters.links
      }, null, _parent));
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/Encounters.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
