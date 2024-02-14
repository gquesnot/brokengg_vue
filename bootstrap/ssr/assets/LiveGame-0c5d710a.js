import { defineComponent, resolveComponent, mergeProps, unref, withCtx, createVNode, toDisplayString, useSSRContext, createTextVNode } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList } from "vue/server-renderer";
import { g as getSummoner, u as urlChampionHelper, w as withoutTagLine, a as urlProPlayerHelper, _ as _sfc_main$2 } from "./SummonerHeader-7f8813f6.js";
import { P as PrimaryButton } from "./PrimaryButton-cbcf38ef.js";
import { useForm, router } from "@inertiajs/vue3";
import "vue3-datepicker";
import "./ResponsiveNavLink-d4a8b54d.js";
import "moment";
import "./AlertApi-748a9f42.js";
import "./_plugin-vue_export-helper-cc2b3d55.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "LiveGameRowPart",
  __ssrInlineRender: true,
  props: {
    is_my_team: { type: Boolean },
    participant: {}
  },
  setup(__props) {
    getSummoner();
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      const _component_VImg = resolveComponent("VImg");
      const _component_v_tooltip = resolveComponent("v-tooltip");
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: `flex pl-2  border-l-4 py-1 w-1/2 ${_ctx.is_my_team ? "border-blue-500" : "border-red-500"}`
      }, _attrs))}>`);
      if (_ctx.participant["champion"]) {
        _push(`<div class="w-8 h-8">`);
        _push(ssrRenderComponent(_component_VImg, {
          src: unref(urlChampionHelper)(_ctx.participant["champion"]["img_url"]),
          class: "w-8 h-8"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="w-25 truncate ml-4">${ssrInterpolate(unref(withoutTagLine)(_ctx.participant["summoner"]["name"]))}</div><div class="ml-4 w-10">`);
      if (_ctx.participant["summoner"]["id"] != null) {
        _push(`<div><div class="cursor-pointer">${ssrInterpolate(_ctx.participant["encounter_count"])}</div></div>`);
      } else {
        _push(`<div> 0 </div>`);
      }
      _push(`</div>`);
      if (_ctx.participant["summoner"]["id"] != null && ((_a = _ctx.participant["summoner"]) == null ? void 0 : _a.pro_player)) {
        _push(`<div class="flex ml-1 justify-center text-xs"><a${ssrRenderAttr("href", unref(urlProPlayerHelper)(_ctx.participant["summoner"].pro_player.slug))}><div class="bg-purple-800 py-0.5 px-1 rounded"> PRO `);
        _push(ssrRenderComponent(_component_v_tooltip, {
          activator: "parent",
          location: "bottom",
          class: "text-center"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            var _a2, _b, _c, _d;
            if (_push2) {
              _push2(`<p${_scopeId}>${ssrInterpolate((_a2 = _ctx.participant["summoner"].pro_player) == null ? void 0 : _a2.team_name)}</p><p${_scopeId}>${ssrInterpolate((_b = _ctx.participant["summoner"].pro_player) == null ? void 0 : _b.name)}</p>`);
            } else {
              return [
                createVNode("p", null, toDisplayString((_c = _ctx.participant["summoner"].pro_player) == null ? void 0 : _c.team_name), 1),
                createVNode("p", null, toDisplayString((_d = _ctx.participant["summoner"].pro_player) == null ? void 0 : _d.name), 1)
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
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/LiveGameRowPart.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "LiveGame",
  __ssrInlineRender: true,
  props: {
    live_game: {},
    fake_live_game: {}
  },
  setup(__props) {
    const summoner = getSummoner();
    const form = useForm({
      lobby_search: "Random Iron #EUW joined the lobby\nNos Unforgiven #EUW joined the lobby\n"
    });
    const searchLobby = () => {
      router.visit(
        route("summoner.live-game", { summoner_id: summoner.id, lobby_search: form.lobby_search }),
        {
          preserveState: true,
          onError: (error) => {
          }
        }
      );
    };
    return (_ctx, _push, _parent, _attrs) => {
      const _component_VTextarea = resolveComponent("VTextarea");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-7/12 mx-auto my-6" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$2, { tab: "LiveGame" }, null, _parent));
      if (_ctx.live_game !== null) {
        _push(`<div><div class=""><div class="flex"><div>${ssrInterpolate(_ctx.live_game["queue"]["description"])}</div><div class="ml-2">${ssrInterpolate(_ctx.live_game["map"]["description"])}</div><div class="ml-2">${ssrInterpolate(_ctx.live_game["duration"])}</div></div><div class="text-xl font-bold text-blue-3"> Blue Team </div><!--[-->`);
        ssrRenderList(_ctx.live_game["participants"], (participant) => {
          _push(`<div>`);
          if (participant["teamId"] == 100) {
            _push(ssrRenderComponent(_sfc_main$1, {
              is_my_team: true,
              participant
            }, null, _parent));
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        });
        _push(`<!--]--><div class="text-xl font-bold text-red-3 mt-4"> Red Team </div><!--[-->`);
        ssrRenderList(_ctx.live_game["participants"], (participant) => {
          _push(`<div>`);
          if (participant["teamId"] == 200) {
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
        _push(`<div class="flex">`);
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
        _push(ssrRenderComponent(PrimaryButton, {
          onClick: ($event) => unref(router).reload({ preserveState: true, only: ["live_game", "errors"] }),
          class: "ml-4"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Refresh `);
            } else {
              return [
                createTextVNode(" Refresh ")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div></div>`);
        if (_ctx.fake_live_game) {
          _push(`<div class="flex items-center flex-col"><!--[-->`);
          ssrRenderList(_ctx.fake_live_game["participants"], (participant) => {
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
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Summoner/LiveGame.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
