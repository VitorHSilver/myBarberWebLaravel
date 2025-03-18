<script setup lang="ts">
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { Button, useToast } from "primevue";
import DatePicker from "primevue/datepicker";

interface User {
    id: number;
    name: string;
    email: string;
    fone: string;
    role: string;
    created_at?: Date;
    updated_at?: Date;
}

interface Form {
    name: string;
    email: string;
    fone: string;
    date: string;
    time: string;
}

defineProps<{
    visible: boolean;
    setVisible: (value: boolean) => void;
}>();
const toast = useToast();
const page = usePage();
const user = computed(() => page.props.auth.user as User);
const currentDate = new Date().toLocaleDateString("en-CA");

const {
    form,
    showTimeSelect,
    notificationError,
    formatPhoneNumber,
    checkDate,
    cleanField,
    dateInput,
    timesSlot,
} = useAppointmentForm({
    initialData: {
        name: user.value.name || "",
        email: user.value.email || "",
        fone: user.value.fone || "",
        date: currentDate,
        time: "",
    } as Form,
});

// Função para enviar o formulário
const submitForm = (setVisible: (value: boolean) => void) => {
    let dateString = "";
    if (form.date instanceof Date) {
        dateString = form.date.toISOString().split("T")[0];
    } else if (typeof form.date === "string") {
        dateString = form.date.split("T")[0];
    } else {
        console.error("form.date não é um formato válido:", form.date);
        return;
    }
    form.date = dateString;

    // Validação básica no frontend
    if (!form.date) {
        return;
    }
    if (!form.time && showTimeSelect.value) {
        toast.add({
            severity: "error",
            summary: "Erro",
            detail: "Por favor, selecione um horário",
            life: 3000,
        });
        return;
    }

    form.post("/appointments", {
        onSuccess: () => {
            setVisible(false);
            toast.add({
                severity: "success",
                summary: "Consulta marcada!",
                detail: "Para alterar a data do agendamento, por favor, crie uma conta.",
                life: 5000,
            });
        },
        onError: (errors: any) => {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail:  "Selecione uma data",
                life: 3000,
            });
            console.log(errors);
        },
    });
};
</script>

<template>
    <h2 class="text-center md:text-4xl text-2xl">Agendamento</h2>
    <form
        @submit.prevent="submitForm(setVisible)"
        class="p-4 flex flex-col gap-4"
    >
        <div class="grid justify-center gap-8 items-center">
            <DatePicker
                v-model="form.date"
                inline
                showWeek
                class="w-full sm:w-[30rem]"
                @update:model-value="checkDate"
            />
            <div v-show="showTimeSelect" class="mt-2 sm:w-[30rem]">
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
        <div class="flex gap-x-4 justify-end mt-2">
            <Button
                type="button"
                label="Cancel"
                severity="contrast"
                @click="setVisible(false)"
            />
            <Button
                type="submit"
                severity="success"
                size="small"
                label="Agendar"
            />
        </div>
    </form>
</template>
<style scoped>
input:focus {
    border-color: gray;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
