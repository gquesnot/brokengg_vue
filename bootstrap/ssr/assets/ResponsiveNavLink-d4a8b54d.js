import { defineComponent, computed, unref, mergeProps, withCtx, renderSlot, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderSlot } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ResponsiveNavLink",
  __ssrInlineRender: true,
  props: {
    href: {},
    active: { type: Boolean }
  },
  setup(__props) {
    const props = __props;
    const classes = computed(
      () => props.active ? "block w-full pl-3 pr-4 py-2 border-b-2 border-gray-200 text-left text-base font-medium text-gray-200 focus:outline-none focus:text-indigo-800  focus:bg-zinc-700 focus:border-zinc-700 transition duration-150 ease-in-out" : "block w-full pl-3 pr-4 py-2  text-left text-base font-medium text-gray-200 hover:bg-zinc-800  focus:outline-none  focus:bg-zinc-700 focus:border-zinc-700 transition duration-150 ease-in-out"
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Link), mergeProps({
        href: _ctx.href,
        class: classes.value
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
          } else {
            return [
              renderSlot(_ctx.$slots, "default")
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ResponsiveNavLink.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
