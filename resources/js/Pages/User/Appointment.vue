<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import Menubar from "primevue/menubar";
import Dialog from "primevue/dialog";
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { Button } from "primevue";
import MiniFormEdit from "./miniFormEdit.vue";
import Toast from "@/Layouts/Toast.vue";
import { Appointment } from "@/types/appointment";
interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

const page = usePage();
const user = computed<User>(() => page.props.auth.user as User);

const appointments = computed<Appointment[]>(
    () => (page.props.appointments as Appointment[]) || []
);

const getMenuItems = () => {
    if (user.value.role === "user") {
        return [
            {
                separator: true,
            },
            {
                label: "Agenda",
                icon: "pi pi-calendar",
                items: [
                    {
                        label: "Alterar Datas",
                        icon: "pi pi-calendar-plus",
                        route: route("appointments.show"),
                    },
                    {
                        label: "Cancelar Consulta",
                        icon: "pi pi-calendar-minus",
                        // route: route("appointment.cancel"), // Ajuste a rota conforme necessário
                    },
                ],
            },
            {
                label: "Lista",
                icon: "pi pi-list",
                items: [
                    {
                        label: "Consultas",
                        icon: "pi pi-user-plus",
                        // route: route("appointments.list"), // Ajuste a rota conforme necessário
                    },
                ],
            },
            {
                label: "Sair",
                icon: "pi pi-sign-out",
                command: () => {
                    router.post(route("logout"));
                },
            },
            {
                separator: true,
            },
        ];
    }
    return [];
};
const items = ref(getMenuItems());

const formatDatePtBr = (dateString: string) => {
    const date = new Date(dateString);
    const day = String(date.getUTCDate()).padStart(2, "0");
    const month = String(date.getUTCMonth() + 1).padStart(2, "0"); // getUTCMonth() é 0-based
    const year = date.getUTCFullYear();
    return `${day}/${month}/${year}`;
};
const selectedAppointment = ref<Appointment | null>(null);

//modal
const setVisible = (value: boolean) => {
    visible.value = value;
};
const visible = ref(false);
</script>

<template>
    <Head title="Alterar Datas" />
    <Toast />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Bem vindo, {{ $page.props.auth.user.name }}
            </h2>
        </template>
        <Menubar :model="items" class="lg:justify-center">
            <template #item="{ item, props }">
                <Link
                    v-if="item.route"
                    :href="item.route"
                    v-bind="props.action"
                >
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </Link>
                <a
                    v-else
                    :href="item.url"
                    :target="item.target"
                    v-bind="props.action"
                >
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
            </template>
        </Menubar>
        <main class="grid md:grid-cols-3 justify-items-center">
            <ul v-for="appointment in appointments" :key="appointment.id">
                <Button
                    :label="formatDatePtBr(appointment.date)"
                    severity="info"
                    size="large"
                    class="mt-4"
                    @click="
                        selectedAppointment = appointment;
                        setVisible(true);
                    "
                />
            </ul>
            <Dialog
                v-model:visible="visible"
                :modal="true"
                :style="{ width: '50vw' }"
                :breakpoints="{ '960px': '75vw', '640px': '100vw' }"
                @hide="setVisible(false)"
            >
                <MiniFormEdit
                    v-if="selectedAppointment"
                    :visible="visible"
                    :set-visible="setVisible"
                    :appointment="selectedAppointment"
                />
            </Dialog>
        </main>
    </AuthenticatedLayout>
</template>
