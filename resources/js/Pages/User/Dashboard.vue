<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import MiniForm from "@/components/miniForm.vue";
import Toast from "@/Layouts/Toast.vue";
import { Link } from "@inertiajs/vue3";

interface User {
    id: number;
    name: string;
    email: string;
    fone: string;
    role: string;
    created_at?: Date;
    updated_at?: Date;
}
interface Appointment {
    id: number;
    user_id: number;
    date: string;
    time: string;
    created_at?: Date;
    updated_at?: Date;
}

const page = usePage();
const user = computed<User>(() => page.props.auth.user as User);
const appointments = computed<Appointment[]>(
    () => page.props.appointments as Appointment[]
);
const appointment_id = appointments.value.map((item) => item.id);

const visible = ref(false);
const setVisible = (value: boolean) => {
    visible.value = value;
};

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
                        route: route("appointments.show"),
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
</script>

<template>
    <Head title="Dashboard" />
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
        <main class="flex justify-center">
            <div class="md:max-w-96 flex">
                <Button
                    label="Agendar Corte"
                    severity="info"
                    size="large"
                    class="mt-4"
                    @click="setVisible(true)"
                />
            </div>
            <Dialog
                v-model:visible="visible"
                :modal="true"
                :style="{ width: '50vw' }"
                :breakpoints="{ '960px': '75vw', '640px': '100vw' }"
                @hide="setVisible(false)"
            >
                <MiniForm :visible="visible" :set-visible="setVisible" />
            </Dialog>
        </main>
        <div class="flex justify-center text-5xl">
            <ul class="mt-4">
                <h2 class="text-center mb-4">Últimos Horarios:</h2>
                <li
                    class="mb-2"
                    v-for="appointment in appointments"
                    :key="appointment.id"
                >
                    {{ formatDatePtBr(appointment.date) }} -
                    {{ appointment.time.slice(0, 5) }}
                </li>
            </ul>
        </div>

        <!-- Modal de Agendamento -->
    </AuthenticatedLayout>
</template>
