import { ref, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import { useForm } from "@inertiajs/vue3";
import { formatPhoneNumber } from "@/utils/phoneUtils";
import { fetchTimeSlots } from "@/services/appointmentService";
import { useDateValidation } from "@/composables/useDateValidation";

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

    const { isDateValid, checkDate } = useDateValidation();

    const form = useForm({
        name: props.initialData?.name || "",
        email: props.initialData?.email || "",
        fone: props.initialData?.fone || "",
        date: props.initialData?.date || currentDate,
        time: props.initialData?.time || "",
    });

    const formatPhone = (): void => {
        form.fone = formatPhoneNumber(form.fone);
    };

    const cleanField = (): void => {
        form.reset();
        form.date = currentDate;
        isDateValid.value = true;
    };

   const validateAndFetchTimeSlots = async (date: string | Date) => {
    let dateString: string;

    // Garante que a data seja uma string no formato "YYYY-MM-DD"
    if (date instanceof Date) {
        dateString = date.toISOString().split("T")[0];
    } else if (typeof date === "string") {
        dateString = date;
    } else {
        console.error("Formato de data inválido:", date);
        return;
    }
    try {
        const isValid = await checkDate(dateString, toast);

        if (isValid) {
            const slots = await fetchTimeSlots(dateString);
            timesSlot.value = slots;
            showTimeSelect.value = true;
        } else {
            console.log("Data inválida, horários não serão carregados.");
            showTimeSelect.value = false;
            timesSlot.value = [];
        }
    } catch (error) {
        console.error("Erro ao validar ou buscar horários:", error);
        showTimeSelect.value = false;
        timesSlot.value = [];
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
        formatPhone,
        validateAndFetchTimeSlots,
        cleanField,
        showVideo,
        dateInput,
        isDateValid,
    };
}
