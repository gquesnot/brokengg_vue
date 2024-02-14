import { defineComponent, onMounted, ref, resolveComponent, unref, withCtx, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { usePage, useForm, Head, router } from "@inertiajs/vue3";
import Datepicker from "vue3-datepicker";
import { P as PrimaryButton } from "./PrimaryButton-cbcf38ef.js";
import { _ as _sfc_main$1 } from "./ResponsiveNavLink-d4a8b54d.js";
import moment from "moment";
import { _ as _sfc_main$2 } from "./AlertApi-748a9f42.js";
const getParamsWithFilters = (filters, other_params = {}) => {
  let new_filters = {
    champion_id: filters.champion_id,
    queue_id: filters.queue_id,
    start_date: filters.start_date,
    end_date: filters.end_date
  };
  if (filters.should_filter_encounters) {
    new_filters = { ...new_filters, ...{ should_filter_encounters: filters.should_filter_encounters } };
  }
  return {
    ...other_params,
    filters: new_filters
  };
};
const getFilters = () => {
  const base_filters = usePage().props.filters;
  return {
    champion_id: base_filters.champion_id ? parseInt(base_filters.champion_id) : void 0,
    queue_id: base_filters.queue_id ? parseInt(base_filters.queue_id) : void 0,
    start_date: base_filters.start_date,
    end_date: base_filters.end_date,
    should_filter_encounters: base_filters.should_filter_encounters == "1"
  };
};
const getSummoner = () => usePage().props.summoner;
const getVersion = () => usePage().props.version;
const getOnly = () => usePage().props.only;
const getRouteParams = () => usePage().props.route_params;
const getChampionOptions = () => usePage().props.champion_options;
const getQueueOptions = () => usePage().props.queue_options;
const urlChampionHelper = (img_url) => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/champion/${img_url}`;
const urlItemHelper = (img_url) => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/item/${img_url}`;
const urlProfilIconHelper = (img_id) => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/profileicon/${img_id.toString()}.png`;
const urlSummonerSpellHelper = (img_url) => `https://ddragon.leagueoflegends.com/cdn/${getVersion()}/img/spell/${img_url}`;
const urlPerkHelper = (img_url) => `https://ddragon.leagueoflegends.com/cdn/img/perk-images/${img_url}`;
const urlProPlayerHelper = (slug) => `https://lolpros.gg/player/${slug}`;
const withoutTagLine = (summoner_name) => {
  return summoner_name.split("#")[0];
};
const tagLineOnly = (summoner_name) => {
  return summoner_name.split("#")[1];
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "SummonerHeader",
  __ssrInlineRender: true,
  props: {
    tab: {}
  },
  setup(__props) {
    const props = __props;
    const filters = getFilters();
    const champion_options = getChampionOptions();
    const queue_options = getQueueOptions();
    let refresh_interval = null;
    const refresh_summoner = () => {
      router.visit(route(route().current(), getParamsWithFilters(getFilters(), getRouteParams())), {
        preserveState: true,
        preserveScroll: true,
        only: getOnly()
      });
    };
    onMounted(() => {
      window.Echo.channel("summoner." + getSummoner().id).listen("SummonerUpdated", ({ should_start_refresh }) => {
        if (should_start_refresh) {
          refresh_summoner();
          refresh_interval = setInterval(refresh_summoner, 3e3);
        } else {
          clearInterval(refresh_interval);
        }
      });
    });
    const form = useForm({
      filters: {
        queue_id: filters.queue_id,
        champion_id: filters.champion_id,
        start_date: filters.start_date ? new Date(filters.start_date) : void 0,
        end_date: filters.end_date ? new Date(filters.end_date) : void 0,
        should_filter_encounters: filters.should_filter_encounters
      }
    });
    const tabs = ref([
      { label: "Matches", route: "summoner.matches" },
      { label: "Champions", route: "summoner.champions" },
      { label: "Encounters", route: "summoner.encounters" },
      { label: "LiveGame", route: "summoner.live-game" }
    ]);
    const updateSummoner = () => {
      router.patch(route("summoner.update", { summoner: getSummoner().id }));
    };
    const applyFilter = () => {
      let new_filters = formToFilters();
      usePage().props.filters = new_filters;
      router.visit(route(route().current(), getParamsWithFilters(new_filters, getRouteParams())), {
        preserveState: true,
        only: getOnly()
      });
    };
    const getTab = (label) => {
      return tabs.value.find((tab) => tab.label === label);
    };
    const clearFilter = () => {
      form.filters.queue_id = void 0;
      form.filters.champion_id = void 0;
      form.filters.start_date = void 0;
      form.filters.end_date = void 0;
      form.filters.should_filter_encounters = false;
      usePage().props.filters = formToFilters();
      router.visit(route(route().current(), getRouteParams()), {
        preserveState: true,
        only: getOnly()
      });
    };
    const formToFilters = () => {
      let filters2 = {
        queue_id: form.filters.queue_id,
        champion_id: form.filters.champion_id,
        start_date: form.filters.start_date ? form.filters.start_date.toISOString() : void 0,
        end_date: form.filters.end_date ? form.filters.end_date.toISOString() : void 0,
        should_filter_encounters: form.filters.should_filter_encounters ? form.filters.should_filter_encounters : false
      };
      if (filters2.start_date !== void 0) {
        filters2.start_date = moment(filters2.start_date.split("T")[0]).add(1, "days").format("YYYY-MM-DD");
      }
      if (filters2.end_date !== void 0) {
        filters2.end_date = moment(filters2.end_date.split("T")[0]).add(1, "days").format("YYYY-MM-DD");
      }
      return filters2;
    };
    const switchTab = (label) => {
      let tab = getTab(label);
      if (tab) {
        router.visit(route(tab.route, {
          summoner_id: getSummoner().id
        }), {
          preserveState: true
        });
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b;
      const _component_VImg = resolveComponent("VImg");
      const _component_VAutocomplete = resolveComponent("VAutocomplete");
      const _component_v_switch = resolveComponent("v-switch");
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: unref(getSummoner)().name
      }, null, _parent));
      _push(`<div class="flex justify-center"><a${ssrRenderAttr("href", _ctx.route("home"))}><div class="text-3xl font-bold mb-8"> BROKEN.GG</div></a></div><div class=""><div class="flex justify-between"><div class="flex mt-4"><div class="w-20">`);
      _push(ssrRenderComponent(_component_VImg, {
        width: 150,
        height: 80,
        src: unref(urlProfilIconHelper)(unref(getSummoner)().profile_icon_id)
      }, null, _parent));
      _push(`</div><div class="ml-4 flex flex-col font-bold text-xl"><div class="cursor-pointer">${ssrInterpolate(unref(withoutTagLine)(unref(getSummoner)().name))} <span class="text-gray-400">#${ssrInterpolate(unref(tagLineOnly)(unref(getSummoner)().name))}</span></div><div class="mb-1 text-base">`);
      if (unref(getSummoner)().solo_q) {
        _push(`<!--[-->${ssrInterpolate((_a = unref(getSummoner)().solo_q) == null ? void 0 : _a.tier)} ${ssrInterpolate((_b = unref(getSummoner)().solo_q) == null ? void 0 : _b.rank)}<!--]-->`);
      } else {
        _push(`<!--[--> lvl ${ssrInterpolate(unref(getSummoner)().summoner_level)}<!--]-->`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: updateSummoner,
        class: "justify-center"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Update `);
          } else {
            return [
              createTextVNode(" Update ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div><div class="bg-gray-1 w-1/2 p-4 text-gray-200 rounded"><div class="flex"><div class="w-1/2"><div>`);
      _push(ssrRenderComponent(_component_VAutocomplete, {
        modelValue: unref(form).filters.queue_id,
        "onUpdate:modelValue": ($event) => unref(form).filters.queue_id = $event,
        items: unref(queue_options),
        "item-value": "value",
        "item-title": "label",
        label: "Queue",
        id: "queue",
        density: "comfortable",
        name: "queue",
        class: "mt-4",
        clearable: true
      }, null, _parent));
      _push(`</div><div>`);
      _push(ssrRenderComponent(_component_VAutocomplete, {
        modelValue: unref(form).filters.champion_id,
        "onUpdate:modelValue": ($event) => unref(form).filters.champion_id = $event,
        items: unref(champion_options),
        "item-value": "value",
        "item-title": "label",
        label: "Champion",
        density: "comfortable",
        id: "champion_id",
        name: "champion_id",
        class: "",
        clearable: true
      }, null, _parent));
      _push(`</div><div>`);
      _push(ssrRenderComponent(_component_v_switch, {
        label: "Filter Encounters",
        modelValue: unref(form).filters.should_filter_encounters,
        "onUpdate:modelValue": ($event) => unref(form).filters.should_filter_encounters = $event
      }, null, _parent));
      _push(`</div></div><div class="w-1/2 ml-4"><div><label for="start_time">Start Date</label>`);
      _push(ssrRenderComponent(unref(Datepicker), {
        modelValue: unref(form).filters.start_date,
        "onUpdate:modelValue": ($event) => unref(form).filters.start_date = $event,
        id: "start_time",
        name: "start_time",
        class: "text-gray-5",
        clearable: "",
        format: ["YYYY-MM-DD"],
        "input-props": { placeholder: "YYYY-MM-DD" }
      }, null, _parent));
      _push(`</div><div class="mt-1.5"><label for="end_time">End Date</label>`);
      _push(ssrRenderComponent(unref(Datepicker), {
        modelValue: unref(form).filters.end_date,
        "onUpdate:modelValue": ($event) => unref(form).filters.end_date = $event,
        id: "end_time",
        name: "end_time",
        class: "text-gray-5",
        clearable: "",
        format: ["YYYY-MM-DD"],
        "input-props": { placeholder: "YYYY-MM-DD" }
      }, null, _parent));
      _push(`</div></div></div><div class="flex">`);
      _push(ssrRenderComponent(PrimaryButton, { onClick: applyFilter }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Apply `);
          } else {
            return [
              createTextVNode(" Apply ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: clearFilter,
        class: "ml-4"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Clear `);
          } else {
            return [
              createTextVNode(" Clear ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div></div></div><div class="grid grid-cols-6 w-1/2 mr-auto mb-4"><!--[-->`);
      ssrRenderList(tabs.value, (tab) => {
        _push(`<div>`);
        _push(ssrRenderComponent(_sfc_main$1, {
          class: "flex items-center justify-center",
          onClick: ($event) => switchTab(tab.label),
          href: "#",
          active: tab.label == props.tab
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(tab.label)}`);
            } else {
              return [
                createTextVNode(toDisplayString(tab.label), 1)
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(`</div>`);
      });
      _push(`<!--]--></div>`);
      _push(ssrRenderComponent(_sfc_main$2, null, null, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/SummonerHeader.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _,
  urlProPlayerHelper as a,
  getParamsWithFilters as b,
  getFilters as c,
  getOnly as d,
  urlProfilIconHelper as e,
  getRouteParams as f,
  getSummoner as g,
  urlSummonerSpellHelper as h,
  urlPerkHelper as i,
  urlItemHelper as j,
  urlChampionHelper as u,
  withoutTagLine as w
};
