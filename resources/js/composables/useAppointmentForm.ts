import { ref, reactive, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";

export function useAppointmentForm(props: { timesSlot?: string[] } = {}) {
    const currentDate = new Date().toLocaleDateString("en-CA");
    const showTimeSelect = ref(false);
    const notificationError = ref(false);
    const timesSlot = ref<string[]>([]);
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

    const isBusinessDay = (dateString: string) => {
        const date = new Date(dateString).getDay();
        return date !== 0 && date !== 6;
    };

    const checkDate = (event: Event) => {
        const target = event.target as HTMLInputElement;
        form.date = target.value;

        if (target.value < currentDate) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Data inválida.",
                life: 3000,
            });
            showTimeSelect.value = false;
            isDateValid.value = false;
        } else if (!isBusinessDay(target.value)) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "A data selecionada não é um dia útil.",
                life: 3000,
            });
            showTimeSelect.value = false;
            isDateValid.value = false;
        } else {
            showTimeSelect.value = true;
            isDateValid.value = true;
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
