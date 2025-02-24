<script setup lang="ts">
import { ref } from "vue";

// import Button from "primevue/button";

const isMenuVisible = ref(false);

function toggleMenu() {
    isMenuVisible.value = !isMenuVisible.value;
}
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
                ></div>
                <ul
                    class="md:flex gap-x-4 font-roboto max-md:text-3xl text-xl max-md:z-50 max-md:absolute max-md:divide-y-2 max-md:divide-white/10 max-md:p-8 lg:gap-8 max-md:w-full"
                >
                    <li class="animate-slide-in opacity-0 animate-1">
                        <a
                            @click="toggleMenu"
                            class="text-white rounded-lg md:p-2 md:hover:text-white/80 font-medium p-4 block md:px-2 md:py-2 md:after:content-[''] md:after:absolute md:after:bottom-0 md:after:left-1/2 md:after:h-0.5 after:w-0 md:after:bg-current md:after:transition-all md:after:duration-300 md:hover:after:w-full md:hover:after:left-0 md:transition ease-in md:hover:scale-110 max-md:hover:bg-white/10"
                            href="#home"
                            >Home</a
                        >
                    </li>
                    <li class="animate-slide-in opacity-0 animate-2">
                        <a
                            @click="toggleMenu"
                            class="text-white rounded-lg md:p-2 md:hover:text-white/80 font-medium p-4 block md:px-2 md:py-2 md:after:content-[''] md:after:absolute md:after:bottom-0 md:after:left-1/2 md:after:h-0.5 after:w-0 md:after:bg-current md:after:transition-all md:after:duration-300 md:hover:after:w-full md:hover:after:left-0 md:transition ease-in md:hover:scale-110 max-md:hover:bg-white/10"
                            href="/"
                            >Serviços</a
                        >
                    </li>
                    <li class="animate-slide-in opacity-0 animate-3">
                        <a
                            @click="toggleMenu"
                            class="text-white rounded-lg md:p-2 md:hover:text-white/80 font-medium p-4 block md:px-2 md:py-2 md:after:content-[''] md:after:absolute md:after:bottom-0 md:after:left-1/2 md:after:h-0.5 after:w-0 md:after:bg-current md:after:transition-all md:after:duration-300 md:hover:after:w-full md:hover:after:left-0 md:transition ease-in md:hover:scale-110 max-md:hover:bg-white/10"
                            href="/"
                            >Sobre</a
                        >
                    </li>
                    <li class="animate-slide-in opacity-0 animate-4">
                        <a
                            @click="toggleMenu"
                            class="btn text-white rounded-lg md:p-2 md:hover:text-white/80 font-medium p-4 block md:px-2 md:py-2 md:after:content-[''] md:after:absolute md:after:bottom-0 md:after:left-1/2 md:after:h-0.5 after:w-0 md:after:bg-current md:after:transition-all md:after:duration-300 md:hover:after:w-full md:hover:after:left-0 md:transition ease-in md:hover:scale-110 max-md:hover:bg-white/10"
                            href="/"
                            >Contatar</a
                        >
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
        <!-- <img src="./../assets/menu.svg" class="block p-2"></img> -->
    </div>
</template>

<style scoped>
#logo {
    max-width: 400px;
    max-height: 200px;
}
ul :nth-child(4) {
    margin-left: 20%;
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
</style>
