<script setup lang="ts">
import NavBar from "@/components/NavBar.vue";
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { Head } from "@inertiajs/vue3";
import Toast from "@/Layouts/Toast.vue";
import { defineAsyncComponent, onMounted, ref } from "vue";
import { isLoading, LoadingStatus } from "@/lib/utils";
import LoadingSpinner from "@/components/LoadingSpinner.vue";

// Definindo o componente AppointmentForm como assíncrono
const AppointmentFormAsync = defineAsyncComponent(
    () => import("@/components/AppointmentForm.vue")
);

const { showVideo } = useAppointmentForm();

const isBackgroundLoaded = ref(false);

const loadingStatus = new LoadingStatus();

const onBackgroundLoaded = () => {
    isBackgroundLoaded.value = true;
    if (isBackgroundLoaded.value) {
        loadingStatus.finishLoading();
    }
};

onMounted(() => {
    loadingStatus.initLoading();
    // O Suspense gerencia o carregamento, mas podemos adicionar um tempo mínimo
    setTimeout(() => {
        if (isBackgroundLoaded.value) {
            loadingStatus.finishLoading();
        }
    }, 2000); // Tempo mínimo de 2 segundos
});
</script>

<template>
    <Head title="Home" />
    <NavBar />
    <Toast />
    <main class="conteudo container" id="home">
        <Suspense>
            <!-- Conteúdo principal -->
            <div
                class="relative max-2xl:pt-32 px-8 pb-16 overflow-hidden text-white bg-gradient-to-t from-marrom-950/40 max-smallscreen:bg-gradient-to-tr max-smallscreen:from-transparent rounded-lg"
            >
                <video
                    v-if="showVideo"
                    id="video"
                    autoplay
                    muted
                    playsinline
                    loop
                    class="absolute inset-0 object-cover -z-10 size-full shadow-sm shadow-slate-700 m-auto mt-6 rounded-lg max-smallscreen:w-full max-smallscreen:h-44"
                    width="1280"
                    height="720"
                    src="output.webm"
                    aria-label="Vídeo de fundo mostrando um barbeiro trabalhando"
                    @loadeddata="onBackgroundLoaded"
                ></video>
                <img
                    v-else
                    src="bg-barber.jpg"
                    class="absolute inset-0 object-cover -z-10 size-full shadow-sm shadow-slate-700 m-auto mt-6 rounded-lg max-smallscreen:w-full max-smallscreen:h-44"
                    alt="Imagem de fundo mostrando uma barbearia tradicional"
                    @load="onBackgroundLoaded"
                />

                <!-- Usar o componente assíncrono -->
                <AppointmentFormAsync />

                <div
                    class="flex max-md:justify-start justify-end max-smallscreen:mt-8"
                >
                    <h1
                        class="font-neuton text-6xl max-sm:text-5xl max-smallscreen:text-4xl max-w-sm text-balance max-smallscreen:text-center max-smallscreen:m-auto md:mr-28 max-lg:m-auto max-md:m-auto max-md:mt-10"
                        aria-label="Subtitulo:Onde a Tradição Encontrará o Estilo Moderno"
                    >
                        Onde a Tradição Encontrará o Estilo Moderno
                    </h1>
                </div>
            </div>

            <!-- Fallback (exibido enquanto o conteúdo carrega) -->
            <template #fallback>
                <LoadingSpinner />
            </template>
        </Suspense>
    </main>
</template>

<style scoped>
.date-input::-webkit-calendar-picker-indicator {
    filter: invert(1);
}

@media (max-width: 950px) {
    .conteudo {
        max-width: 1024px;
    }
}

@media (max-width: 350px) {
    .conteudo {
        min-width: 40vh;
    }
}
</style>