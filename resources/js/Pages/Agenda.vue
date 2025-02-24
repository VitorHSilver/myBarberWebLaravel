<script setup lang="ts">
import { onMounted, ref } from "vue";
import NavBar from "@/components/NavBar.vue";
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { Head } from "@inertiajs/vue3";
import AppointmentForm from "@/components/AppointmentForm.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useToast } from "primevue";


const { form, timesSlot } = useAppointmentForm();
const showVideo = ref(true);
const currentDate = new Date().toLocaleDateString("en-CA");
const toast = useToast();

onMounted(() => {
    fetchTimes();
    setInterval(() => {
        if (form.date === currentDate) {
            fetchTimes();
        }
    }, 60000);
    if (window.innerWidth < 768) {
        showVideo.value = false;
    }
});

const fetchTimes = async () => {
    try {
        const response = await fetch(
            `http://localhost:3000/api/consults/free-times?date=${form.date}`
        );
        if (!response.ok)
            throw new Error("Erro ao buscar os horários disponíveis");
        const data = await response.json();
        timesSlot.value = data.times;
        console.log(data);
        if (form.hour && !timesSlot.value.includes(form.hour)) {
            form.hour = "";
        } else if (!form.hour && timesSlot.value.length > 0) {
            form.hour = timesSlot.value[0];
        }
    } catch (erro) {
        console.error("Erro:", erro);
    }
};


</script>
<template>
    <Head title="Home" />
    <NavBar />
    <AppLayout />
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
                src="/6113143-hd_1280_720_60fps.mp4"
            ></video>
            <img
                v-else
                src="bg-barber.jpg"
                class="absolute inset-0 object-cover -z-10 size-full shadow-sm shadow-slate-700 m-auto mt-6 rounded-lg max-smallscreen:w-full max-smallscreen:h-44"
                alt=""
            />

            <AppointmentForm />

            <div class="flex justify-end max-smallscreen:mt-8">
                <h1
                    class="font-neuton text-6xl max-sm:text-4xl max-w-sm text-balance max-smallscreen:text-center max-smallscreen:m-auto"
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
