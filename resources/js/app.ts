import "../css/app.css";
import "./bootstrap";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, DefineComponent, h } from "vue";
import PrimeVue from "primevue/config";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import Aura from "@primeuix/themes/aura";
import ToastService from "primevue/toastservice";
import "primeicons/primeicons.css";
import DialogService from "primevue/dialogservice";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} | ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin)
            .use(ZiggyVue)
            .use(DialogService)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                },
                locale: {
                    // Adicionando a configuração de locale
                    dayNames: [
                        "Domingo",
                        "Segunda",
                        "Terça",
                        "Quarta",
                        "Quinta",
                        "Sexta",
                        "Sábado",
                    ],
                    dayNamesShort: [
                        "Dom",
                        "Seg",
                        "Ter",
                        "Qua",
                        "Qui",
                        "Sex",
                        "Sáb",
                    ],
                    dayNamesMin: ["D", "S", "T", "Q", "Q", "S", "S"],
                    monthNames: [
                        "Janeiro",
                        "Fevereiro",
                        "Março",
                        "Abril",
                        "Maio",
                        "Junho",
                        "Julho",
                        "Agosto",
                        "Setembro",
                        "Outubro",
                        "Novembro",
                        "Dezembro",
                    ],
                    monthNamesShort: [
                        "Jan",
                        "Fev",
                        "Mar",
                        "Abr",
                        "Mai",
                        "Jun",
                        "Jul",
                        "Ago",
                        "Set",
                        "Out",
                        "Nov",
                        "Dez",
                    ],
                    today: "Hoje",
                    weekHeader: "Sem",
                    firstDayOfWeek: 0, 
                    dateFormat: "dd/mm/yy", 
                },
            })
            .use(ToastService);

        app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
