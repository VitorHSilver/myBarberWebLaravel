import { ref, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

export function useAppointmentForm(props: { timesSlot?: string[] } = {}) {
    const currentDate = new Date().toLocaleDateString("en-CA");
    const showTimeSelect = ref(false);
    const notificationError = ref(false);
    const timesSlot = ref<string[]>(props.timesSlot || []);
    const showVideo = ref(true);
    const toast = useToast();
    const dateInput = ref<HTMLInputElement | null>(null);
    const isDateValid = ref(true);

    const form = useForm({
        name: "",
        email: "",
        fone: "",
        date: currentDate,
        time: "",
    });

    const formatPhoneNumber = () => {
        let fone = form.fone.replace(/\D/g, "");
        if (fone.length > 11) {
            fone = fone.slice(0, 11);
            dateInput.value?.focus();
        }
        if (fone.length === 11) {
            form.fone = `(${fone.slice(0, 2)}) ${fone.slice(2, 7)}-${fone.slice(
                7
            )}`;
        } else if (fone.length === 10) {
            form.fone = `(${fone.slice(0, 2)}) ${fone.slice(2, 6)}-${fone.slice(
                6
            )}`;
        } else {
            form.fone = fone;
        }
    };

    const cleanField = () => {
        form.reset();
        form.date = currentDate;
        isDateValid.value = true;
    };

    const isBusinessDay = async (dateString: string): Promise<boolean> => {
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                throw new Error("Data inválida fornecida");
            }

            const year = date.getFullYear();
            const response = await axios.get(
                `https://brasilapi.com.br/api/feriados/v1/${year}`
            );
            const holidays = response.data;
            const holidayDates = holidays.map(
                (item: { date: string }) => item.date
            );

            const dayOfWeek = date.getDay();
            const formattedDate = date.toISOString().split("T")[0];

            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            const isHoliday = holidayDates.includes(formattedDate);

            return !isWeekend && !isHoliday;
        } catch (error) {
            console.error("Erro ao verificar dia útil:", error);
            return false;
        }
    };

    const fetchTimeSlots = async (date: string) => {
        try {
            const response = await axios.get(`/free-times?date=${date}`);
            timesSlot.value = response.data.times; // Atualiza timesSlot com os horários retornados
        } catch (error) {
            console.error("Erro ao buscar horários:", error);
            timesSlot.value = []; // Em caso de erro, limpa os horários
        }
    };
    const checkDate = async (event: Event) => {
        const target = event.target as HTMLInputElement;
        form.date = target.value;

        const currentDate = new Date().toISOString().split("T")[0];

        if (target.value < currentDate) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Data inválida.",
                life: 3000,
            });
            showTimeSelect.value = false;
            isDateValid.value = false;
        } else {
            const isValidBusinessDay = await isBusinessDay(target.value);
            if (!isValidBusinessDay) {
                toast.add({
                    severity: "error",
                    summary: "Erro",
                    detail: "A data selecionada não é um dia útil.",
                    life: 3000,
                });
                showTimeSelect.value = false;
                isDateValid.value = false;
            } else {
                await fetchTimeSlots(target.value);
                showTimeSelect.value = true;
                isDateValid.value = true;
            }
        }
    };

    onMounted(() => {
        if (window.innerWidth < 768) {
            showVideo.value = false;
        }
    });

    return {
        form,
        timesSlot,
        showTimeSelect,
        notificationError,
        formatPhoneNumber,
        checkDate,
        cleanField,
        showVideo,
        dateInput,
        isDateValid,
    };
}
