<script setup lang="ts">
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { Button, useToast } from "primevue";
import DatePicker from "primevue/datepicker";

interface Appointment {
    id: number;
    date: string;
    time: string;
    name: string;
    email: string;
    fone: string;
}

const props = defineProps<{
    appointment: Appointment;
    visible: boolean;
    setVisible: (value: boolean) => void;
}>();

const toast = useToast();

const form = useForm({
    date: new Date(props.appointment.date),
    time: props.appointment.time,
    name: props.appointment.name,
    email: props.appointment.email,
    fone: props.appointment.fone,
});

const { showTimeSelect, validateAndFetchTimeSlots, timesSlot } =
    useAppointmentForm({
        initialData: {
            date: props.appointment.date,
            time: props.appointment.time,
            name: props.appointment.name,
            email: props.appointment.email,
            fone: props.appointment.fone,
        },
    });

onMounted(() => {
    validateAndFetchTimeSlots(props.appointment.date);
});

// Função para enviar o formulário
const submitForm = () => {
    let dateString = "";
    if (form.date instanceof Date) {
        dateString = form.date.toISOString().split("T")[0];
    } else if (typeof form.date === "string") {
        dateString = form.date;
    } else {
        console.error("form.date não é um formato válido:", form.date);
        return;
    }

    if (!form.date) {
        console.log("Erro: Data não selecionada"); // Adicione este log
        toast.add({
            severity: "error",
            summary: "Erro",
            detail: "Por favor, selecione uma data",
            life: 3000,
        });
        return;
    }
    if (!form.time && showTimeSelect.value) {
        console.log("Erro: Horário não selecionado"); // Adicione este log
        toast.add({
            severity: "error",
            summary: "Erro",
            detail: "Por favor, selecione um horário",
            life: 3000,
        });
        return;
    }

    form.put(route("appointments.update", props.appointment.id), {
        onSuccess: () => {
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
                detail: "Selecione um data",
                life: 3000,
            });
            console.log(errors);
        },
    });
};
const formatDatePtBr = (dateString: string) => {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0"); // Usa getDate() para o dia local
    const month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() é 0-based
    const year = date.getFullYear(); // Usa getFullYear() para o ano local
    return `${day}/${month}/${year}`;
};

</script>

<template>
    <h2 class="text-center md:text-4xl text-2xl">Alterar</h2>
    <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-4">
        <div class="grid justify-center gap-8 items-center">
            <DatePicker
                v-model="form.date"
                inline
                showWeek
                class="w-full sm:w-[30rem]"
                @update:model-value="validateAndFetchTimeSlots(form.date)"
                dateFormat="yy-mm-dd"
    
            />
            <div v-show="showTimeSelect" class="mt-2 sm:w-[30rem]">
                <label for>Horarios</label>
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
            <p
                class="font-bold flex justify-between items-center max-md:flex-col"
            >
                Sua nova será:
                <span class="text-2xl text-emerald-600">
                    {{ formatDatePtBr(form.date) }} - {{ form.time }}</span
                >
            </p>
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
                label="Salvar"
            />
        </div>
    </form>
</template>
<style scoped>
/* remover bordas */
select:focus,
input:focus {
    border-color: inherit;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
