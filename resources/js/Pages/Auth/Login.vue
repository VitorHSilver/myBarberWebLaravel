<script setup lang="ts">
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import FloatLabel from "primevue/floatlabel";
import InputText from "primevue/inputtext";
import { onMounted, ref } from "vue";

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
            if (form.remember) {
                const email = form.email;
                localStorage.setItem("Email", email);
            }
        },
    });
};
const passwordVisible = ref(false);

const fillInEmail = (): string => {
    return localStorage.getItem("Email") || "";
};
onMounted(() => {
    const savedEmail = fillInEmail();
    if (savedEmail) {
        form.email = savedEmail;
    }
});
</script>

<template>
    <Head title="Login" />

    <GuestLayout>
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <FloatLabel variant="over">
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

            <div class="mt-8 relative">
                <FloatLabel variant="over">
                    <InputText
                        id="password"
                        :type="passwordVisible ? 'text' : 'password'"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <div class="absolute top-2 right-2">
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

                    <label for="password">Senha</label>
                </FloatLabel>
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
                        Ou faça login com sua conta
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

<style scoped>
:deep(.p-inputtext) {
    background-color: #fff !important;
    color: #000 !important;
    border: 1px solid #ccc !important;
}

:deep(.p-inputtext:focus) {
    border-color: #9f9f9f !important;
    box-shadow: none !important;
}

label {
    color: #9f9f9f !important;
}

input:focus {
    border-color: gray;
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
