import { defineComponent, ref, onMounted, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs } from "vue/server-renderer";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "TextInput",
  __ssrInlineRender: true,
  props: {
    modelValue: {}
  },
  emits: ["update:modelValue"],
  setup(__props, { expose: __expose }) {
    const input = ref(null);
    onMounted(() => {
      var _a, _b;
      if ((_a = input.value) == null ? void 0 : _a.hasAttribute("autofocus")) {
        (_b = input.value) == null ? void 0 : _b.focus();
      }
    });
    __expose({ focus: () => {
      var _a;
      return (_a = input.value) == null ? void 0 : _a.focus();
    } });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<input${ssrRenderAttrs(mergeProps({
        class: "border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm",
        value: _ctx.modelValue,
        ref_key: "input",
        ref: input
      }, _attrs))}>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/TextInput.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
