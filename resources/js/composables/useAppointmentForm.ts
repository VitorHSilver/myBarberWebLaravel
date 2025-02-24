import { ref, reactive, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import { date } from "@primeuix/themes/aura/datepicker";

export function useAppointmentForm() {
    const currentDate = new Date().toLocaleDateString("en-CA");
    const showTimeSelect = ref(false);
    const notificationError = ref(false);
    const timesSlot = ref<string[]>([]);
    const toast = useToast();

    const form = reactive({
        name: "",
        email: "",
        fone: "",
        date: currentDate,
        hour: "",
    });

    const fetchTimes = async () => {
    console.log("tá aqui?", currentDate);

        try {
            const response = await fetch(
                `http://localhost:3000/api/consults/free-times?date=${form.date}`
            );
            if (!response.ok)
                throw new Error("Erro ao buscar os horários disponíveis");
            const data = await response.json();
            timesSlot.value = data.times;
            if (form.hour && !timesSlot.value.includes(form.hour)) {
                form.hour = "";
            } else if (!form.hour && timesSlot.value.length > 0) {
                form.hour = timesSlot.value[0];
            }
        } catch (error) {
            console.error("Erro:", error);
        }
    };

    const handleSubmit = async () => {
        
        if (!form.name || !form.date || !form.fone || !form.hour) {
            notificationError.value = true;
            setTimeout(() => {
                notificationError.value = false;
            }, 3000);
            return;
        }

        try {
            const response = await fetch("http://localhost:3000/api/consults", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(form),
            });
            const data = await response.json();

            if (response.ok) {
                cleanField();
                toast.add({
                    severity: "success",
                    summary: "Consulta marcada!",
                    detail: data.message,
                    life: 3000,
                });
            } else {
                toast.add({
                    severity: "error",
                    summary: "Erro",
                    detail: `${data.erro}`,
                    life: 3000,
                });
            }
        } catch (error) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Erro ao enviar os dados.",
                life: 3000,
            });
            console.error(error);
        }
    };

    const formatPhoneNumber = () => {
        let fone = form.fone.replace(/\D/g, "");
        if (fone.length > 11) {
            fone = fone.slice(0, 11);
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
        form.hour = "";
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
        } else if (!isBusinessDay(target.value)) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "A data selecionada não é um dia útil.",
                life: 3000,
            });
            showTimeSelect.value = false;
        } else {
            showTimeSelect.value = true;
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
    });

    return {
        form,
        timesSlot,
        showTimeSelect,
        notificationError,
        fetchTimes,
        handleSubmit,
        formatPhoneNumber,
        checkDate,
        cleanField,
    };
}
