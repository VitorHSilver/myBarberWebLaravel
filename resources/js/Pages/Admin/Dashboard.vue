<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import Menubar from "primevue/menubar";
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

const page = usePage();
const user = computed<User>(() => page.props.auth.user as User);

const getMenuItems = () => {
    if (user.value.role === "admin") {
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
                label: "Profissionais",
                icon: "pi pi-cog",
                items: [
                    {
                        label: "Adicionar",
                        icon: "pi pi-user-plus",
                    },
                    {
                        label: "Alterar função",
                        icon: "pi pi-user-edit",
                    },
                    {
                        label: "Mostrar Todos",
                        icon: "pi pi-users",
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
                <Link
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
                </Link>
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
        <main>
            Calendario Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Sunt omnis debitis velit ducimus tenetur amet eos repudiandae quasi,
            explicabo possimus iusto facere sint illo aspernatur! Ipsum adipisci
            eum neque autem.
        </main>
    </AuthenticatedLayout>
</template>
