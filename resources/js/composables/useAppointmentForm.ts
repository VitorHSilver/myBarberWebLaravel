import { ref, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { TimelineSlots } from "primevue";

export function useAppointmentForm(
    props: {
        timesSlot?: string[];
        initialData?: {
            name: string;
            email: string;
            fone: string;
            date: string;
            time: string;
        };
    } = {}
) {
    const currentDate = new Date().toLocaleDateString("en-CA");
    const showTimeSelect = ref(false);
    const notificationError = ref(false);
    const timesSlot = ref<string[]>(props.timesSlot || []);
    const showVideo = ref(true);
    const toast = useToast();
    const dateInput = ref<HTMLInputElement | null>(null);
    const isDateValid = ref(true);

    const form = useForm({
        name: props.initialData?.name || "",
        email: props.initialData?.email || "",
        fone: props.initialData?.fone || "",
        date: props.initialData?.date || currentDate,
        time: props.initialData?.time || "",
    });

    const formatPhoneNumber = (): void => {
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

    const cleanField = (): void => {
        form.reset();
        form.date = currentDate;
        isDateValid.value = true;
    };

    interface Holidays {
        date: string;
        name: string;
        type: string;
    }

    const isBusinessDay = async (dateString: string): Promise<boolean> => {
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                throw new Error("Data inválida fornecida");
            }

            const year = date.getFullYear();
            const response = await axios.get<Holidays[]>(
                `https://brasilapi.com.br/api/feriados/v1/${year}`
            );
            const holidays: Holidays[] = response.data;
            const holidayDates = holidays.map((item: Holidays) => item.date);

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
    type TimeSlots = { times: string[] };

    const fetchTimeSlots = async (date: string): Promise<TimeSlots> => {
        try {
            const url = `/api/available-times?date=${encodeURIComponent(date)}`; // URL absoluta
            const response = await axios.get<TimeSlots>(url);
            timesSlot.value = response.data.times;
            return response.data;
        } catch (error) {
            console.error("Erro ao buscar horários:", error);
            timesSlot.value = [];
            return { times: [] };
        }
    };
    const checkDate = async (value: string | Date) => {
        const selectedDate =
            form.date instanceof Date
                ? form.date.toISOString().split("T")[0]
                : typeof form.date === "string"
                ? form.date
                : "";

        if (!selectedDate) {
            console.error("Data não fornecida ou inválida:", form.date);
            return;
        }
        const currentDate = new Date().toISOString().split("T")[0];

        if (selectedDate < currentDate) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Data inválida.",
                life: 3000,
            });
            showTimeSelect.value = false;
            isDateValid.value = false;
        } else {
            const isValidBusinessDay = await isBusinessDay(selectedDate);
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
                await fetchTimeSlots(selectedDate);
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
