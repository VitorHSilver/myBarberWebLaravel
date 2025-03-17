<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import { User } from "lucide-vue-next";
import Dialog from "primevue/dialog";

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at?: Date;
    updated_at?: Date;
}

const page = usePage();
const user = computed<User>(() => page.props.auth.user as User);
const openModal = ref(false);

const scheduleAppointment = () => {
    openModal.value = !openModal.value;
    console.log(user.value.name);
};

const form = useForm({
    name: user.value.name || "",
});
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
                    },
                    {
                        label: "Cancelar Consulta",
                        icon: "pi pi-calendar-minus",
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
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Bem vindo, {{ $page.props.auth.user.name }}
            </h2>
        </template>
        <Menubar :model="items" class="lg:justify-center">
            <template #item="{ item, props }">
                <router-link
                    v-if="item.route"
                    v-slot="{ href, navigate }"
                    :to="item.route"
                    custom
                >
                    <a
                        v-ripple
                        :href="href"
                        v-bind="props.action"
                        @click="navigate"
                    >
                        <span :class="item.icon" />
                        <span class="ml-2">{{ item.label }}</span>
                    </a>
                </router-link>
                <a
                    v-else
                    v-ripple
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
                    size="small"
                    class="mt-4"
                    @click="scheduleAppointment"
                />
            </div>
        </main>
    </AuthenticatedLayout>
</template>
