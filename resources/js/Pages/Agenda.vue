<script setup lang="ts">
import NavBar from "@/components/NavBar.vue";
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { Head } from "@inertiajs/vue3";
import AppointmentForm from "@/components/AppointmentForm.vue";
import Toast from "@/Layouts/Toast.vue";

const { showVideo } = useAppointmentForm();
</script>
<template>
    <Head title="Home" />
    <NavBar />
    <Toast />
    <main class="conteudo container" id="home">
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
            ></video>
            <img
                v-else
                src="bg-barber.jpg"
                class="absolute inset-0 object-cover -z-10 size-full shadow-sm shadow-slate-700 m-auto mt-6 rounded-lg max-smallscreen:w-full max-smallscreen:h-44"
                alt="Imagem de fundo mostrando uma barbearia tradicional"
            />

            <AppointmentForm />

            <div class="flex max-md:justify-start justify-end max-smallscreen:mt-8">
                <h1
                    class="font-neuton text-6xl max-sm:text-4xl max-w-sm text-balance max-smallscreen:text-center max-smallscreen:m-auto"
                    aria-label="Subtitulo:Onde a Tradição Encontrará o Estilo Moderno"
                >
                    Onde a Tradição Encontrará o Estilo Moderno
                </h1>
            </div>
        </div>
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
