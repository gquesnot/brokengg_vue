import { defineComponent, ref, resolveComponent, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent } from "vue/server-renderer";
import { usePage } from "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "AlertApi",
  __ssrInlineRender: true,
  setup(__props) {
    const is_open = ref(false);
    let alert_message = ref(void 0);
    setInterval(() => {
      if (usePage().props.errors.api) {
        alert_message.value = usePage().props.errors.api;
        is_open.value = true;
        usePage().props.errors = {};
      }
    }, 500);
    return (_ctx, _push, _parent, _attrs) => {
      const _component_v_alert = resolveComponent("v-alert");
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed right-10 bottom-10 z-20" }, _attrs))}>`);
      _push(ssrRenderComponent(_component_v_alert, {
        modelValue: is_open.value,
        "onUpdate:modelValue": ($event) => is_open.value = $event,
        border: "start",
        type: "error",
        text: unref(alert_message),
        closable: ""
      }, null, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/AlertApi.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
