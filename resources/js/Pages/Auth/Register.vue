<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout2.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useAppointmentForm } from "@/composables/useAppointmentForm";

const form = useForm({
    name: "",
    email: "",
    fone: "",
    password: "",
    password_confirmation: "",
});
const { formatPhoneNumber } = useAppointmentForm();
const passwordVisible = ref(false);
const passwordVisibleTwo = ref(false);

const submit = () => {
    form.post(route("register"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
        onError: (erro) => {
            console.log(erro);
        },
    });
};

const validatePasswordConfirmation = (): void => {
    if (form.password && form.password_confirmation) {
        if (form.password !== form.password_confirmation) {
            form.errors.password_confirmation = "As senhas não coincidem.";
        } else if (form.password_confirmation !== form.password) {
            form.errors.password_confirmation = "As senhas não coincidem.";
        } else {
            form.errors.password_confirmation = "";
        }
    } else {
        form.errors.password_confirmation = "";
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Cadastro" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nome" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="fone" value="Telefone" />

                <TextInput
                    id="fone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.fone"
                    @input="
                        form.errors.fone = undefined;
                        formatPhoneNumber();
                    "
                    placeholder="DD Número"
                    required
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.fone" />
            </div>

            <div class="mt-4 relative">
                <InputLabel for="password" value="Senha" />

                <TextInput
                    id="password"
                    :type="passwordVisible ? 'text' : 'password'"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    @change="validatePasswordConfirmation"
                    required
                    autocomplete="new-password"
                />
                <div class="absolute top-8 right-2">
                    <img
                        :src="
                            passwordVisible
                                ? 'icon/eye.svg'
                                : 'icon/eye_off.svg'
                        "
                        class="cursor-pointer"
                        @click="passwordVisible = !passwordVisible"
                    />
                </div>

                <InputError
                    class="mt-2 font-bold"
                    :message="form.errors.password"
                />
            </div>

            <div class="mt-4 relative">
                <InputLabel
                    for="password_confirmation"
                    value="Confirme sua Senha"
                />

                <TextInput
                    id="password_confirmation"
                    :type="passwordVisibleTwo ? 'text' : 'password'"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    @change="validatePasswordConfirmation"
                />
                <div class="absolute top-8 right-2">
                    <img
                        :src="
                            passwordVisibleTwo
                                ? 'icon/eye.svg'
                                : 'icon/eye_off.svg'
                        "
                        class="cursor-pointer"
                        @click="passwordVisibleTwo = !passwordVisibleTwo"
                    />
                </div>

                <InputError
                    class="mt-2 font-bold"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Já tem uma conta?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
<style scoped>
input:focus {
    border-color: gray;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
