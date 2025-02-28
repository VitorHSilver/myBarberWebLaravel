<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import { LogOut } from "lucide-vue-next";
import { computed, ref, resolveDirective } from "vue";

const isMenuVisible = ref(false);

function toggleMenu() {
    isMenuVisible.value = !isMenuVisible.value;
}
const page = usePage();
const user = computed(() => page.props.auth.user);
const isHomePage = computed(() => page.component === "Agenda");

const navLinks = [
    {
        label: "Home",
        href: route("home"),
        condition: () => !isHomePage.value,
        ariaLabel: "Ir para a página inicial",
        animation: "animate-1",
        order: "",
        onClick: toggleMenu,
        fav: "",
    },
    {
        label: "Serviços",
        href: "",
        condition: () => true,
        ariaLabel: "Ir para serviços",
        animation: "animate-1",
        order: "",
        onClick: toggleMenu,
        fav: "", // Sem ícone por enquanto
    },
    {
        label: "Sobre",
        href: "",
        condition: () => true,
        ariaLabel: "Ir para sobre",
        animation: "animate-2",
        order: "",
        onClick: toggleMenu,
    },
    {
        label: "Contatar",
        href: "",
        condition: () => true,
        ariaLabel: "Ir para contatar",
        animation: "animate-3",
        order: "",
        onClick: toggleMenu,
    },
    {
        label: "Login",
        href: route("login"),
        condition: () => user.value === null,
        ariaLabel: "Fazer login",
        animation: "animate-4",
        order: "max-md:order-[-1]",
        onClick: toggleMenu,
    },
    {
        label: "Minha Conta",
        href: route("dashboard"),
        condition: () => user.value !== null,
        ariaLabel: "Sair da conta",
        animation: "animate-4",
        order: "max-md:order-[-1]",
        onClick: () => {},
    },
];
const filteredNavLinks = computed(() =>
    navLinks.filter((link) => link.condition())
);
</script>

<template>
    <div class="container">
        <header
            class="grid grid-cols-2 mt-6 items-center max-md:flex max-md:justify-around"
        >
            <a href="./">
                <img
                    id="logo"
                    src="/Frame.svg"
                    alt="logo"
                    class="md:m-auto max-smallscreen:w-44 lg:w-72"
                />
            </a>
            <nav
                id="mobile-menu"
                :class="{ hidden: !isMenuVisible, flex: isMenuVisible }"
                class="md:block flex items-center z-40 max-md:fixed max-md:w-full max-md:inset-0"
                data-testid="mobile-menu"
            >
                <div
                    class="fixed md:hidden inset-0 bg-marrom-200/2 backdrop-blur-md"
                    @click="isMenuVisible = false"
                ></div>
                <ul
                    class="md:flex gap-x-4 font-roboto max-md:text-3xl text-xl max-md:z-50 max-md:absolute max-md:divide-y-2 max-md:divide-white/10 max-md:p-8 lg:gap-8 max-md:w-full"
                >
                    <li
                        v-for="(link, index) in filteredNavLinks"
                        :key="index"
                        :class="[
                            'animate-slide-in',
                            'opacity-0',
                            link.animation,
                            link.order,
                            {
                                'lg:ml-8':
                                    index === filteredNavLinks.length - 1,
                            },
                        ]"
                        :style="{ animationDelay: `${index * 0.2}s` }"
                    >
                        <a
                            @click.stop="link.onClick"
                            class="text-white rounded-lg md:p-2 md:hover:text-white/80 font-medium p-4 block md:px-2 md:py-2 md:after:content-[''] md:after:absolute md:after:bottom-0 md:after:left-1/2 md:after:h-0.5 after:w-0 md:after:bg-current md:after:transition-all md:after:duration-300 md:hover:after:w-full md:hover:after:left-0 md:transition ease-in md:hover:scale-110 max-md:hover:bg-white/10"
                            :href="link.href"
                            :aria-label="link.ariaLabel"
                        >
                            <img
                                v-if="link.fav"
                                :src="link.fav"
                                alt="ícone"
                                class="w-5 h-5"
                            />
                            <span>{{ link.label }}</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <button
                id="mobile-button"
                @click="toggleMenu"
                class="flex items-center bg-white text-marrom-300 py-2 gap-3 px-4 rounded-full md:hidden"
            >
                <span
                    class="h-3 w-4 flex flex-col *:h-0.5 *:rounded-md justify-between *:bg-black"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </header>
    </div>
</template>

<style scoped>
#logo {
    max-width: 400px;
    max-height: 200px;
}
/* Remover o sublinhado do último link */
ul li:last-child a::after {
    content: none !important;
}

/* Estilização específica para o quarto link (Login ou Minha Conta) */
ul li:nth-child(4) a {
    margin-left: 20%;
}

@media (min-width: 768px) {
    ul li:nth-child(4) a {
        transition: all 0.5s ease;
        color: #fff;
        border: 3px solid white;
        text-transform: uppercase;
        text-align: center;
        line-height: 1;
        font-size: 17px;
        background-color: transparent;
        padding: 10px;
        outline: none;
        border-radius: 4px;
        margin-left: 0%;
        letter-spacing: 0.4rem;
    }

    ul li:nth-child(4) a:hover {
        color: #001f3f;
        background-color: #fff;
    }
}
@media (max-width: 1160px) {
    ul :nth-child(4) {
        margin-left: 4%;
    }
}
@media (max-width: 977px) {
    ul :nth-child(4) {
        margin-left: 0%;
    }
}
@media (max-width: 878px) {
    ul :nth-child(4) {
        margin-left: 0%;
    }
}
@media (max-width: 768px) {
    ul li:nth-child(4) a {
        margin-left: 0%;
    }
}
</style>
