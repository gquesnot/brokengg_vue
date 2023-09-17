import { resolveComponent, unref, withCtx, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { router, usePage, useForm } from "@inertiajs/vue3";
import { P as PrimaryButton } from "./PrimaryButton-d82933f3.js";
import { _ as _sfc_main$1 } from "./InputError-be673cc6.js";
import { _ as _sfc_main$2 } from "./ResponsiveNavLink-2d39c768.js";
import Datepicker from "vue3-datepicker";
const getParams = (filters, base_params) => {
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
    ...base_params,
    filters: new_filters
  };
};
const navigateToEncounter = (summoner_id, encounter_id) => {
  let route_params = {
    summoner: summoner_id,
    encounter: encounter_id
  };
  router.visit(route("summoner.encounter", route_params), {
    preserveState: true
  });
};
const navigateToMatch = (summoner_id, match_id) => {
  let route_params = {
    summoner: summoner_id,
    match: match_id
  };
  router.visit(route("summoner.match", route_params), {
    preserveState: true
  });
};
const getSummoner = () => usePage().props.summoner;
const getFilters = () => {
  let filters = usePage().props.filters;
  if (filters.queue_id) {
    filters.queue_id = parseInt(filters.queue_id);
  }
  if (filters.champion_id) {
    filters.champion_id = parseInt(filters.champion_id);
  }
  filters.should_filter_encounters = filters.should_filter_encounters === "1";
  return filters;
};
const getVersion = () => usePage().props.version;
const getOnly = () => usePage().props.only;
const getRouteParams = () => usePage().props.route_params;
const getChampionOptions = () => usePage().props.champion_options;
const getQueueOptions = () => usePage().props.queue_options;
const _sfc_main = {
  __name: "SummonerHeader",
  __ssrInlineRender: true,
  props: {
    tab: {
      type: String
    }
  },
  setup(__props) {
    const props = __props;
    const summoner = getSummoner();
    let filters = getFilters();
    const champion_options = getChampionOptions();
    const queue_options = getQueueOptions();
    const only = getOnly();
    const route_params = getRouteParams();
    const version = getVersion();
    const form = useForm({
      filters: {
        queue_id: filters.queue_id,
        champion_id: filters.champion_id,
        start_date: filters.start_date ? new Date(filters.start_date) : void 0,
        end_date: filters.end_date ? new Date(filters.end_date) : void 0,
        should_filter_encounters: filters.should_filter_encounters
      }
    });
    const tabs = [
      { label: "Matches", route: "summoner.matches" },
      { label: "Champions", route: "summoner.champions" },
      { label: "Encounters", route: "summoner.encounters" },
      { label: "LiveGame", route: "summoner.live-game" }
    ];
    const updateSummoner = () => {
      router.patch(route("summoner.update", { summoner: summoner.id }));
    };
    const applyFilter = () => {
      let new_filters = formToFilters();
      usePage().props.filters = filters = form.filters;
      router.visit(route(route().current(), getParams(new_filters, route_params)), {
        preserveState: true,
        only
      });
    };
    const clearFilter = () => {
      form.filters.queue_id = void 0;
      form.filters.champion_id = void 0;
      form.filters.start_date = void 0;
      form.filters.end_date = void 0;
      form.filters.should_filter_encounters = false;
      usePage().props.filters = filters = form.filters;
      let new_filters = formToFilters();
      router.visit(route(route().current(), getParams(new_filters, route_params)), {
        preserveState: true,
        only,
        preserveScroll: true
      });
    };
    const getTab = (label) => {
      return tabs.find((tab) => tab.label === label);
    };
    const formToFilters = () => {
      return {
        queue_id: form.filters.queue_id ? form.filters.queue_id.toString() : void 0,
        champion_id: form.filters.champion_id ? form.filters.champion_id.toString() : void 0,
        start_date: form.filters.start_date ? form.filters.start_date.toISOString() : void 0,
        end_date: form.filters.end_date ? form.filters.end_date.toISOString() : void 0,
        should_filter_encounters: form.filters.should_filter_encounters ? form.filters.should_filter_encounters : false
      };
    };
    const switchTab = (label) => {
      let tab = getTab(label);
      if (tab) {
        form.tab = tab.label;
        router.visit(route(tab.route, getParams(usePage().props.filters, route_params ?? {})), {
          preserveState: true,
          preserveScroll: true
        });
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n;
      const _component_Head = resolveComponent("Head");
      const _component_VImg = resolveComponent("VImg");
      const _component_VAutocomplete = resolveComponent("VAutocomplete");
      const _component_v_switch = resolveComponent("v-switch");
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_component_Head, {
        title: unref(summoner).name
      }, null, _parent));
      _push(`<div class="flex justify-center"><a${ssrRenderAttr("href", _ctx.route("home"))}><div class="text-3xl font-bold mb-8"> BROKEN.GG</div></a></div><div class=""><div class="flex justify-between"><div class="flex mt-4"><div class="w-16">`);
      _push(ssrRenderComponent(_component_VImg, {
        width: 75,
        height: 75,
        src: `https://ddragon.leagueoflegends.com/cdn/${unref(version)}/img/profileicon/${unref(summoner).profile_icon_id.toString()}.png`
      }, null, _parent));
      _push(`</div><div class="ml-4"><div>${ssrInterpolate(unref(summoner).name)}</div>`);
      _push(ssrRenderComponent(PrimaryButton, { onClick: updateSummoner }, {
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
      _push(`</div></div><div class="bg-gray-500 w-1/2 p-4"><div class="flex"><div class="w-1/2"><div>`);
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
        clearable: true,
        "error-messages": (_b = (_a = unref(form).errors) == null ? void 0 : _a.filters) == null ? void 0 : _b.queue_id
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
        clearable: true,
        "error-messages": (_d = (_c = unref(form).errors) == null ? void 0 : _c.filters) == null ? void 0 : _d.champion_id
      }, null, _parent));
      _push(`</div><div>`);
      _push(ssrRenderComponent(_component_v_switch, {
        label: "Filter Encounters",
        modelValue: unref(form).filters.should_filter_encounters,
        "onUpdate:modelValue": ($event) => unref(form).filters.should_filter_encounters = $event,
        "error-messages": (_f = (_e = unref(form).errors) == null ? void 0 : _e.filters) == null ? void 0 : _f.should_filter_encounters
      }, null, _parent));
      _push(`</div></div><div class="w-1/2 ml-4"><div><label for="start_time">Start Time</label>`);
      _push(ssrRenderComponent(unref(Datepicker), {
        modelValue: unref(form).filters.start_date,
        "onUpdate:modelValue": ($event) => unref(form).filters.start_date = $event,
        id: "start_time",
        name: "start_time",
        clearable: "",
        format: ["YYYY-MM-DD"],
        "input-props": { placeholder: "YYYY-MM-DD" },
        "input-class": { "border-red-500": (_h = (_g = unref(form).errors) == null ? void 0 : _g.filters) == null ? void 0 : _h.start_date }
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        message: (_j = (_i = unref(form).errors) == null ? void 0 : _i.filters) == null ? void 0 : _j.start_date
      }, null, _parent));
      _push(`</div><div><label for="end_time">End Time</label>`);
      _push(ssrRenderComponent(unref(Datepicker), {
        modelValue: unref(form).filters.end_date,
        "onUpdate:modelValue": ($event) => unref(form).filters.end_date = $event,
        id: "end_time",
        name: "end_time",
        clearable: "",
        format: ["YYYY-MM-DD"],
        "input-props": { placeholder: "YYYY-MM-DD" },
        "input-class": { "border-red-500": (_l = (_k = unref(form).errors) == null ? void 0 : _k.filters) == null ? void 0 : _l.end_date }
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        message: (_n = (_m = unref(form).errors) == null ? void 0 : _m.filters) == null ? void 0 : _n.end_date
      }, null, _parent));
      _push(`</div></div></div><div class="flex">`);
      _push(ssrRenderComponent(PrimaryButton, {
        onClick: applyFilter,
        class: "ml-4"
      }, {
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
      _push(`</div></div></div></div><div class="grid grid-cols-6 w-1/2 mr-auto"><!--[-->`);
      ssrRenderList(tabs, (tab) => {
        _push(`<div>`);
        _push(ssrRenderComponent(_sfc_main$2, {
          class: "flex items-center justify-center",
          onClick: ($event) => switchTab(tab.label),
          href: "#",
          active: tab.label === props.tab
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
      _push(`<!--]--></div><!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Summoner/SummonerHeader.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _,
  getParams as a,
  getVersion as b,
  getRouteParams as c,
  navigateToMatch as d,
  getSummoner as g,
  navigateToEncounter as n
};
