<script setup lang="ts">
import { useToast } from "primevue/usetoast";
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { Button } from "@/components/ui/button";
import { ref } from "vue";
import InputError from "@/Components/InputError.vue";

const {
    form,
    showTimeSelect,
    notificationError,
    formatPhone,
    validateAndFetchTimeSlots,
    cleanField,
    dateInput,
    timesSlot,
} = useAppointmentForm();

const toast = useToast();
const buttonInput = ref<HTMLInputElement | null>(null);

const handleSubmit = async () => {
    if (!form.name.trim() && !form.email.trim() && !form.fone) {
        notificationError.value = true;
        setTimeout(() => (notificationError.value = false), 3000);
        return;
    }

    form.post("/appointments", {
        onSuccess: () => {
            cleanField();
            toast.add({
                severity: "success",
                summary: "Consulta marcada!",
                detail: "Para alterar a data do agendamento, por favor, crie uma conta.",
                life: 5000,
            });
            
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Por favor, corrija os campos destacados.",
                life: 3000,
            });
        },
    });
};
</script>

<template>
    <form
        @submit.prevent="handleSubmit"
        class="flex flex-col md:flex-row border-2 md:max-w-[21rem] max-md:max-w-[24rem] max-md:p-4 border-gray-100 items-center gap-8 pr-1 pl-2 mb-4 max-smallscreen:border-none max-smallscreen:mt-16 max-lg:mt-0 max-lg:mb-8 xl:mt-24 max-xl:mt-22 max-smallscreen:shadow-none md:ml-32 max-md:grid max-md:gap-4 md:max-md:items-start py-2 animate-slide-in opacity-0 animate-12 rounded-3xl max-md:m-auto"
    >
        <div class="grid gap-4 p-2 max-sm:p-0 max-sm:gap-2 max-md:w-full">
            <h3
                class="md:text-center text-3xl px-8 uppercase max-[400]:bg-pink-100 m-auto max-smallscreen:mt-8 font-semibold max-md:text-center"
            >
                Agende seu Corte
            </h3>
            <div>
                <input
                    type="text"
                    class="w-full bg-transparent border border-gray-100/60 pl-2 mr-2 rounded-md outline-none ring-1 ring-gray-200/80 py-1 placeholder:text-gray-50 max-smallscreen:mt-2 max-sm:mb-1 input relative"
                    placeholder="Nome"
                    autofocus
                    v-model="form.name"
                    @input="form.errors.name = undefined"
                    aria-label="Nome"
                    autocomplete="username"
                />
                <InputError :message="form.errors.name" />
            </div>
            <div>
                <input
                    type="email"
                    class="w-full bg-transparent border border-gray-100/60 pl-2 mr-2 rounded-md outline-none ring-1 ring-gray-200/80 py-1 placeholder:text-gray-50 max-sm:mb-1"
                    placeholder="Email"
                    v-model="form.email"
                    @input="form.errors.email = undefined"
                    aria-label="Email"
                    autocomplete="username"
                />
                <InputError :message="form.errors.email" />
            </div>
            <div>
                <input
                    type="text"
                    class="w-full bg-transparent border border-gray-100/60 pl-2 mr-2 rounded-md outline-none ring-1 ring-gray-200/80 py-1 placeholder:text-gray-50 max-sm:mb-1"
                    placeholder="DD + número"
                    v-model="form.fone"
                    aria-label="Fone"
                    @input="
                        form.errors.fone = undefined;
                        formatPhone();
                    "
                />
                <InputError :message="form.errors.fone" />
            </div>

            <div class="flex max-md:flex-col max-md:gap-4">
                <input
                    type="date"
                    class="date-input flex-grow bg-transparent border border-gray-100/60 pl-2 mr-1 rounded-md outline-none ring-1 ring-gray-200/80 py-1 data-checked:underline text-gray-50 max-md:w-full"
                    v-model="form.date"
                    @change="validateAndFetchTimeSlots(form.date)"
                    @focus="validateAndFetchTimeSlots(form.date)"
                    ref="dateInput"
                />
                <div v-show="showTimeSelect" class="block flex-grow">
                    <select
                        class="max-md:w-full px-1 py-1 bg-transparent border-2 border-gray-100/60 rounded-md *:text-black w-full"
                        v-model="form.time"
                        aria-label="Horario"
                    >
                        <option disabled value="">Horarios</option>
                        <option v-if="timesSlot.length === 0" disabled>
                            Nenhum horário disponível
                        </option>
                        <option
                            v-for="times in timesSlot"
                            :key="times"
                            :value="times"
                        >
                            {{ times }}
                        </option>
                    </select>
                </div>
            </div>
            <Button
                ref="buttonInput"
                class="bg-marrom-500 hover:bg-marrom-600 rounded-md border border-gray-200 max-md:mb-2 text-lg"
                aria-label="Confirmar agendamento"
                :disabled="form.processing"
            >
                {{ form.processing ? "Enviando..." : "Confirmar" }}
            </Button>
            <Transition name="slide-fade">
                <span
                    v-if="notificationError"
                    class="text-orange-300/80 text-base font-bold"
                >
                    Preencha todos os campos obrigatórios!
                </span>
            </Transition>
        </div>
    </form>
</template>
<style scoped>
.date-input::-webkit-calendar-picker-indicator {
    filter: invert(1);
}

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

/* remover bordas */
select:focus,
input:focus {
    border-color: inherit;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
