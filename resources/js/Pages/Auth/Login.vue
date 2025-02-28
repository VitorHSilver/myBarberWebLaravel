<script setup lang="ts">
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
};
</script>

<template>
    <Head title="Barber | Login" />

    <GuestLayout>
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit" class="">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Senha" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="mt-4 flex justify-between">
                <label>
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Me lembrar</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Esqueceu sua senha?
                </Link>
            </div>

            <div class="mt-4 grid items-center text-center">
                <div class="w-full mt-2">
                    <Link
                        v-if="canResetPassword"
                        :href="route('register')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Criar Conta
                    </Link>
                </div>

                <PrimaryButton
                    class="text-center text-lg uppercase bg-black text-white mt-4"
                    :class="{ 'opacity-25': form.processing }"
                    severity="ghost"
                    :disabled="form.processing"
                >
                    Logar
                </PrimaryButton>
                <div class="flex items-center gap-2 my-4">
                    <span class="flex-1 h-px bg-gray-300"></span>
                    <p class="text-gray-500 text-sm">
                        Ou faça login com o seu e-mail
                    </p>
                    <span class="flex-1 h-px bg-gray-300"></span>
                </div>
            </div>
        </form>
        <div class="flex justify-center">
            <a :href="route('auth.google')">
                <button
                    class="inline-flex items-center border rounded-md text-lg p-2 w-auto"
                >
                    <img
                        src="/google.png"
                        width="20px"
                        alt="logo"
                        class="mr-2"
                    />
                    Google
                </button>
            </a>
        </div>
    </GuestLayout>
</template>
