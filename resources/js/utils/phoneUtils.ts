export function formatPhoneNumber(fone: string): string {
    let cleaned = fone.replace(/\D/g, "");
    if (cleaned.length > 11) {
        cleaned = cleaned.slice(0, 11);
    }
    if (cleaned.length === 11) {
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 7)}-${cleaned.slice(7)}`;
    } else if (cleaned.length === 10) {
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 6)}-${cleaned.slice(6)}`;
    }
    return cleaned;
}