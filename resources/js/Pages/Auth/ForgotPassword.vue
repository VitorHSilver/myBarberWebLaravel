<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { FloatLabel, InputText } from "primevue";

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Redefinir Senha" />

        <div class="mb-4 text-sm text-gray-600">
            Esqueceu sua senha? Sem problemas. Basta fornecer-nos o seu endereço
            de email e enviaremos um link para redefinição de senha, permitindo
            que você escolha uma nova senha.
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <FloatLabel variant="on">
                    <InputText
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <label for="email">Email</label>
                </FloatLabel>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Link para redefinição da senha
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
<style scoped>
:deep(.p-inputtext:focus) {
    border-color: #9f9f9f !important;
    box-shadow: none !important;
}
label {
    color: #9f9f9f !important;
}
</style>
