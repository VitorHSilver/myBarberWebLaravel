<script setup lang="ts">
import { useAppointmentForm } from "@/composables/useAppointmentForm";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { Button, useToast } from "primevue";
import DatePicker from "primevue/datepicker";
import { Appointment } from "@/types/appointment";

interface User {
    id: number;
    name: string;
    email: string;
    fone: string;
    role: string;
    created_at?: Date;
    updated_at?: Date;
}

const props = defineProps<{
    visible: boolean;
    setVisible: (value: boolean) => void;
    appointment?: Appointment;
}>();

const toast = useToast();
const page = usePage();
const user = computed<User>(() => page.props.auth.user as User);


// Formulário do Inertia.js para envio
const form = useForm<{
    date: Date; 
    time: string;
    name: string;
    email: string;
    fone: string;
}>({
    date: props.appointment ? new Date(props.appointment.date) : new Date(), // Converte para Date
    time: props.appointment?.time || "",
    name: props.appointment?.name || user.value.name || "",
    email: props.appointment?.email || user.value.email || "",
    fone: props.appointment?.fone || user.value.fone || "",
});

// Composable para gerenciar horários
const { showTimeSelect, validateAndFetchTimeSlots, timesSlot } =
    useAppointmentForm({
        initialData: {
            name: form.name,
            email: form.email,
            fone: form.fone,
            date: form.date.toISOString().split("T")[0], // Converte para string para o composable
            time: form.time,
        },
    });

// Atualiza os horários disponíveis quando a data muda
const onDateUpdate = (
    value: Date | Date[] | (Date | null)[] | null | undefined
) => {
    if (value instanceof Date) {
        const dateString = value.toISOString().split("T")[0];
        validateAndFetchTimeSlots(dateString);
    } else {
        console.warn("Valor inválido para a data:", value);
    }
};

const submitForm = () => {
    // Converte form.date para string no formato YYYY-MM-DD
    const dateString = form.date.toISOString().split("T")[0];

    // Atualiza os dados do form
    form.date = dateString as any; // Temporariamente usamos 'as any' para evitar conflito de tipo
    form.time = form.time;
    form.name = form.name;
    form.email = form.email;
    form.fone = form.fone;

    form.post(route("appointments.store"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Sucesso",
                detail: "Agendamento criado com sucesso!",
                life: 5000,
            });
            props.setVisible(false); // Fecha o modal
        },
        onError: (errors: any) => {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Erro ao criar o agendamento. Verifique os dados.",
                life: 3000,
            });
            console.log(errors);
        },
    });
};
</script>

<template>
    <h2 class="text-center md:text-4xl text-2xl">Agendamento</h2>
    <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-4">
        <div class="grid justify-center gap-8 items-center">
            <DatePicker
                v-model="form.date"
                inline
                showWeek
                class="w-full sm:w-[30rem]"
                @update:model-value="onDateUpdate"
                dateFormat="yy-mm-dd"
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
select:focus,
input:focus {
    border-color: inherit;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
