import { resolveComponent, mergeProps, unref, useSSRContext, withCtx, createTextVNode } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { usePage, useForm, router } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./SummonerHeader-0b1a75fc.js";
import { P as PrimaryButton } from "./PrimaryButton-d82933f3.js";
import { u as urlChampionHelper } from "./url_helpers-8bee5ae0.js";
import "./InputError-be673cc6.js";
import "./ResponsiveNavLink-2d39c768.js";
import "vue3-datepicker";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$1 = {
  __name: "LiveGameRowPart",
  __ssrInlineRender: true,
  props: {
    is_my_team: {
      type: Boolean
    },
    participant: {
      type: Object
    }
  },
  setup(__props) {
    const version = usePage().props.version;
    usePage().props.summoner;
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VImg = resolveComponent("VImg");
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: `flex pl-2  border-l-4 py-1 w-1/2 ${__props.is_my_team ? "border-blue-500" : "border-red-500"}`
      }, _attrs))}>`);
      if (__props.participant["champion"]) {
        _push(`<div class="w-8 h-8">`);
        _push(ssrRenderComponent(_component_VImg, {
          src: unref(urlChampionHelper)(unref(version), __props.participant["champion"]["img_url"]),
          class: "w-8 h-8"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="w-25 truncate ml-4">${ssrInterpolate(__props.participant["summoner"]["name"])}</div><div class="ml-4 w-10">`);
      if (__props.participant["encounter_count"]) {
        _push(`<div><div class="cursor-pointer">${ssrInterpolate(__props.participant["encounter_count"])}</div></div>`);
      } else {
        _push(`<div> 0 </div>`);
      }
      _push(`</div></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/LiveGameRowPart.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "LiveGame",
  __ssrInlineRender: true,
  props: {
    live_game: {
      type: Object
    },
    fake_live_game: {
      type: Object
    }
  },
  setup(__props) {
    const route_params = usePage().props.route_params;
    const form = useForm({
      lobby_search: "nos unforgiven joined the lobby\nrandom iron joined the lobby\n"
    });
    const searchLobby = () => {
      let params = {
        ...route_params,
        lobby_search: form.lobby_search
      };
      router.visit(
        route("summoner.live-game", params),
        {
          preserveState: true
        }
      );
    };
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTextarea = resolveComponent("VTextarea");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$2, { tab: "LiveGame" }, null, _parent));
      _push(`<div class="flex justify-end">`);
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: ($event) => unref(router).reload({ preserveState: true, only: ["live_game"] })
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Refresh`);
          } else {
            return [
              createTextVNode("Refresh")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
      if (__props.live_game !== null) {
        _push(`<div><div class=""><div class="flex"><div>${ssrInterpolate(__props.live_game["queue"]["description"])}</div><div class="ml-2">${ssrInterpolate(__props.live_game["map"]["description"])}</div><div class="ml-2">${ssrInterpolate(__props.live_game["duration"])}</div></div><div class="text-xl font-bold text-blue"> Blue Team </div><!--[-->`);
        ssrRenderList(__props.live_game["participants"], (participant) => {
          _push(`<div>`);
          if (participant["teamId"] === 100) {
            _push(ssrRenderComponent(_sfc_main$1, {
              is_my_team: true,
              participant
            }, null, _parent));
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        });
        _push(`<!--]--><div class="text-xl font-bold text-red mt-4"> Red Team </div><!--[-->`);
        ssrRenderList(__props.live_game["participants"], (participant) => {
          _push(`<div>`);
          if (participant["teamId"] === 200) {
            _push(ssrRenderComponent(_sfc_main$1, {
              is_my_team: false,
              key: participant["summoner"]["id"],
              participant
            }, null, _parent));
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        });
        _push(`<!--]--></div></div>`);
      } else {
        _push(`<div class="flex flex-col w-full justify-center items"><div class="flex flex-col justify-center items-center min-h-[10rem]"><div>Summoner is not in a live game</div>`);
        _push(ssrRenderComponent(_component_VTextarea, {
          modelValue: unref(form).lobby_search,
          "onUpdate:modelValue": ($event) => unref(form).lobby_search = $event,
          class: "my-2 w-1/2 mx-auto",
          placeholder: "Lobby Summoner",
          rows: "5"
        }, null, _parent));
        _push(ssrRenderComponent(PrimaryButton, { onClick: searchLobby }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Search`);
            } else {
              return [
                createTextVNode("Search")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
        if (__props.fake_live_game) {
          _push(`<div class="flex items-center flex-col"><!--[-->`);
          ssrRenderList(__props.fake_live_game["participants"], (participant) => {
            _push(ssrRenderComponent(_sfc_main$1, {
              key: participant["summoner"]["id"],
              is_my_team: true,
              participant
            }, null, _parent));
          });
          _push(`<!--]--></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/LiveGame.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
