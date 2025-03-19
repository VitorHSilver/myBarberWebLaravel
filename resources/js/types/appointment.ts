export interface Appointment {
    id: number;
    user_id: number;
    date: string;
    time: string;
    name: string;
    email: string;
    fone: string;
    created_at?: Date;
    updated_at?: Date;
}
