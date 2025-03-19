import axios from "axios";

export async function fetchTimeSlots(date: string): Promise<string[]> {
    try {
        const url = `/api/available-times?date=${encodeURIComponent(date)}`;
        const response = await axios.get<{ times: string[] }>(url);
        return response.data.times;
    } catch (error) {
        console.error("Erro ao buscar horários:", error);
        return [];
    }
}
