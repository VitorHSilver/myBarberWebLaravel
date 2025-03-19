<script setup lang="ts">
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { Button, useToast } from "primevue";
import DatePicker from "primevue/datepicker";
import { Appointment } from "@/types/appointment";

const props = defineProps<{
    appointment: Appointment;
    visible: boolean;
    setVisible: (value: boolean) => void;
}>();

const toast = useToast();

const form = useForm<{
    date: string | Date;
    time: string;
    name: string;
    email: string;
    fone: string;
}>({
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

const submitForm = () => {
    // Formata a data para YYYY-MM-DD
    let dateString = "";
    if (form.date instanceof Date) {
        const year = form.date.getFullYear();
        const month = String(form.date.getMonth() + 1).padStart(2, "0");
        const day = String(form.date.getDate()).padStart(2, "0");
        dateString = `${year}-${month}-${day}`;
    } else if (typeof form.date === "string") {
        dateString = form.date.split("T")[0];
    } else {
        console.error("Formato inválido para form.date:", form.date);
        toast.add({
            severity: "error",
            summary: "Erro",
            detail: "Formato de data inválido.",
            life: 3000,
        });
        return;
    }

    form.date = dateString;

    form.put(route("appointments.update", props.appointment.id), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Alteração de data!",
                detail: "Alteramento feito com sucesso",
                life: 5000,
            });
        },
        onError: (errors: any) => {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Selecione um Horario",
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
                <label>Horarios</label>
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
                Sua nova data será:
                <span class="text-2xl text-emerald-600">
                    {{ formatDatePtBr(form.date) }} - {{ form.time }}</span
                >
            </p>
        </div>
        <div class="flex gap-x-4 justify-end mt-2">
            <Button
                type="button"
                label="Voltar"
                severity="contrast"
                @click="setVisible(false)"
            />
            <Button
                type="submit"
                severity="success"
                size="small"
                label="Atualizar"
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
