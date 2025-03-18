import axios from "axios";
import { ref } from "vue";

interface Holidays {
    date: string;
    name: string;
    type: string;
}

export function useDateValidation() {
    const isDateValid = ref(true);

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
            const holidays = response.data;
            const holidayDates = holidays.map((item) => item.date);

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

    const checkDate = async (date: string, toast: any) => {
        const currentDate = new Date().toISOString().split("T")[0];

        if (date < currentDate) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "Data inválida.",
                life: 3000,
            });
            isDateValid.value = false;
            return false;
        }

        const isValidBusinessDay = await isBusinessDay(date);
        if (!isValidBusinessDay) {
            toast.add({
                severity: "error",
                summary: "Erro",
                detail: "A data selecionada não é um dia útil.",
                life: 3000,
            });
            isDateValid.value = false;
            return false;
        }

        isDateValid.value = true;
        return true;
    };

    return { isDateValid, checkDate };
}
