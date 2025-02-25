import { ref, reactive, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import axios from "axios";



export function useAppointmentForm() {
    const currentDate = new Date().toLocaleDateString("en-CA");
    const showTimeSelect = ref(false);
    const notificationError = ref(false);
    const timesSlot = ref<string[]>([]);
    const showVideo = ref(true);
    const toast = useToast();
    const dateInput = ref<HTMLInputElement | null>(null);
    const isDateValid = ref(true);

   

    const form = reactive({
        name: "",
        email: "",
        fone: "",
        date: currentDate,
        time: "",
    });

    const fetchTimes = async () => {
        try {
            const response = await axios.get(
                `http://localhost:8000/api/free-times`,
                {
                    params: { date: form.date },
                }
            );
            timesSlot.value = response.data.times;
            if (form.time && !timesSlot.value.includes(form.time)) {
                form.time = "";
            } else if (!form.time && timesSlot.value.length > 0) {
                form.time = timesSlot.value[0];
            }
        } catch (error) {
            console.error("Erro:", error);
        }
    };

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
        form.name = "";
        form.email = "";
        form.fone = "";
        form.date = currentDate;
        form.time = "";
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
            fetchTimes();
        }
    };

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

    return {
        form,
        timesSlot,
        showTimeSelect,
        notificationError,
        fetchTimes,
        formatPhoneNumber,
        checkDate,
        cleanField,
        showVideo,
        dateInput,
        isDateValid,
    };
}
