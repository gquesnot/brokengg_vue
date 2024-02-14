import { defineComponent, resolveComponent, unref, withCtx, createVNode, createTextVNode, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import { useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1, a as _sfc_main$3 } from "./InputLabel-d90c040a.js";
import { _ as _sfc_main$2 } from "./TextInput-107f91f9.js";
import { P as PrimaryButton } from "./PrimaryButton-cbcf38ef.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Welcome",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      summoner_name: "random iron"
    });
    const searchSummoner = () => {
      form.post(route("summoners.store"), {
        preserveScroll: true,
        onSuccess: () => {
        },
        onError: (response) => {
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      const _component_v_card = resolveComponent("v-card");
      const _component_v_card_title = resolveComponent("v-card-title");
      const _component_v_card_text = resolveComponent("v-card-text");
      const _component_v_card_actions = resolveComponent("v-card-actions");
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Accueil" }, null, _parent));
      _push(`<div class="flex justify-center items-center min-h-screen bg-dots-darker bg-center bg-gray-200">`);
      _push(ssrRenderComponent(_component_v_card, { class: "p-6 w-1/2" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_component_v_card_title, { class: "text-center" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span class="text-4xl"${_scopeId2}>BROKEN.GG</span>`);
                } else {
                  return [
                    createVNode("span", { class: "text-4xl" }, "BROKEN.GG")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_v_card_text, { class: "flex flex-col items-center justify-center" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$1, {
                    for: "summoner_name",
                    value: "Search Summoner",
                    class: "mb-2"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$2, {
                    id: "summoner_name",
                    ref: "inputTextSummonerName",
                    modelValue: unref(form).summoner_name,
                    "onUpdate:modelValue": ($event) => unref(form).summoner_name = $event,
                    class: "p-2 w-1/2"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    message: unref(form).errors.summoner_name,
                    class: "mt-2"
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(_sfc_main$1, {
                      for: "summoner_name",
                      value: "Search Summoner",
                      class: "mb-2"
                    }),
                    createVNode(_sfc_main$2, {
                      id: "summoner_name",
                      ref: "inputTextSummonerName",
                      modelValue: unref(form).summoner_name,
                      "onUpdate:modelValue": ($event) => unref(form).summoner_name = $event,
                      class: "p-2 w-1/2"
                    }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                    createVNode(_sfc_main$3, {
                      message: unref(form).errors.summoner_name,
                      class: "mt-2"
                    }, null, 8, ["message"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_component_v_card_actions, { class: "flex justify-center" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(PrimaryButton, {
                    class: "mt-4 text-2xl",
                    onClick: searchSummoner
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(` Search `);
                      } else {
                        return [
                          createTextVNode(" Search ")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(PrimaryButton, {
                      class: "mt-4 text-2xl",
                      onClick: searchSummoner
                    }, {
                      default: withCtx(() => [
                        createTextVNode(" Search ")
                      ]),
                      _: 1
                    })
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_component_v_card_title, { class: "text-center" }, {
                default: withCtx(() => [
                  createVNode("span", { class: "text-4xl" }, "BROKEN.GG")
                ]),
                _: 1
              }),
              createVNode(_component_v_card_text, { class: "flex flex-col items-center justify-center" }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$1, {
                    for: "summoner_name",
                    value: "Search Summoner",
                    class: "mb-2"
                  }),
                  createVNode(_sfc_main$2, {
                    id: "summoner_name",
                    ref: "inputTextSummonerName",
                    modelValue: unref(form).summoner_name,
                    "onUpdate:modelValue": ($event) => unref(form).summoner_name = $event,
                    class: "p-2 w-1/2"
                  }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                  createVNode(_sfc_main$3, {
                    message: unref(form).errors.summoner_name,
                    class: "mt-2"
                  }, null, 8, ["message"])
                ]),
                _: 1
              }),
              createVNode(_component_v_card_actions, { class: "flex justify-center" }, {
                default: withCtx(() => [
                  createVNode(PrimaryButton, {
                    class: "mt-4 text-2xl",
                    onClick: searchSummoner
                  }, {
                    default: withCtx(() => [
                      createTextVNode(" Search ")
                    ]),
                    _: 1
                  })
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><!--]-->`);
    };
  }
});
const Welcome_vue_vue_type_style_index_0_lang = "";
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Welcome.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
