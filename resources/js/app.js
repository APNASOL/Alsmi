import { createApp, h } from "vue";
import { InertiaProgress } from "@inertiajs/progress";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

import toastr from "toastr";
window.toastr = toastr;
toastr.options = {
    positionClass: "toast-bottom-right",
};
InertiaProgress.init();

//  Globally Calling Components
import DeleteModal from "./Pages/Global/DeleteModal.vue";
import ConfirmationModal from "./Pages/Global/ConfirmationModal.vue";
import ImageZooming from "./Pages/Global/ImageZooming.vue";
// import Cropper from './Pages/Global/Cropper.vue';
import CropperOffCanvas from "./Pages/Global/CropperOffCanvas.vue";
import IncomeExpenseCreateComponent from "./Pages/Global/IncomeExpenseCreateComponent.vue";

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .mixin({ methods: { route } })
            .use(plugin)
            .component("Link", Link)
            .component("DeleteModal", DeleteModal)
            .component("ConfirmationModal", ConfirmationModal)
            .component("ImageZooming", ImageZooming)

            .component("CropperOffCanvas", CropperOffCanvas)
            .component(
                "IncomeExpenseCreateComponent",
                IncomeExpenseCreateComponent
            )
            .mount(el);
    },
});
