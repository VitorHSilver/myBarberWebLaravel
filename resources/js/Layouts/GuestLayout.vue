<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref } from "vue";
import Carousel from "primevue/carousel";

const isSmallScreen = ref(false);

function updateScreenSize() {
    isSmallScreen.value = window.innerWidth < 768;
}
onMounted(() => {
    updateScreenSize();
    window.addEventListener("resize", updateScreenSize);
});
onUnmounted(() => {
    window.removeEventListener("resize", updateScreenSize);
});

const images = ref([
    {
        url: "carousel/foto1.jpg",
        name: "barbearia",
    },
    {
        url: "carousel/foto2.png",
        name: "corte",
    },
    {
        url: "carousel/foto3.jpg",
        name: "objetos",
    },
    {
        url: "carousel/foto4.png",
        name: "corte",
    },
    {
        url: "carousel/foto5.jpg",
        name: "corte",
    },
    {
        url: "carousel/foto6.png",
        name: "corte",
    },
]);
</script>

<template>
    <div
        class="lg:grid grid-cols-[1200px_1fr] max-lg:flex flex-col h-screen bg-white"
    >
        <div
            v-if="!isSmallScreen"
            class="h-screen flex justify-center items-center bg-white"
        >
            <Carousel
                :value="images"
                :numVisible="2"
                :numScroll="1"
                :autoplayInterval="5000"
                class="custom-carousel"
            >
                <template #item="slotProps">
                    <div class="w-full h-full">
                        <img
                            :src="slotProps.data.url"
                            :alt="slotProps.data.name || 'Carousel Image'"
                            class="w-full h-full object-cover"
                        />
                    </div>
                </template>
            </Carousel>
        </div>

        <div
            class="flex min-h-screen min-w-96 flex-col items-center pt-6 sm:justify-center sm:pt-0 bg-white max-md:mt-14"
        >
            <div>
                <Link href="/">
                    <ApplicationLogo
                        class="h-20 w-20 fill-current text-gray-500"
                    />
                </Link>
            </div>

            <div
                class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-lg sm:max-w-md sm:rounded-lg"
            >
                <slot />
            </div>
        </div>
    </div>
</template>

<style>
.p-carousel-indicator-active .p-carousel-indicator-button {
    background-color: #4b5563 !important;
}
</style>
