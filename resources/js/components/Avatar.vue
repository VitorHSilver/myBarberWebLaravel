<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
    modelValue?: File | null;
    imageUrl?: string | null;
}>();

const emit = defineEmits<{
    "update:modelValue": [file: File | null];
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(props.imageUrl || null);

const selectImage = () => {
    fileInput.value?.click();
};
const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        emit("update:modelValue", file);

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <div class="flex flex-col items-start mb-6">
        <div
            class="relative w-32 h-32 rounded-full border-4 border-gray-200 overflow-hidden cursor-pointer hover:border-indigo-300 transition-colors duration-200"
            @click="selectImage"
        >
            <div
                class="w-full h-full bg-gray-100 flex items-center justify-center"
            >
                <img
                    v-if="imagePreview"
                    :src="imagePreview"
                    alt="Avatar do usuário"
                    class="w-full h-full object-cover"
                />
                <svg
                    v-else
                    class="w-16 h-16 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>

            <!-- Overlay com ícone de câmera -->
            <div
                class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200"
            >
                <svg
                    class="w-8 h-8 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
            </div>
        </div>

        <!-- Input file oculto -->
        <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handleImageChange"
        />

        <label class="text-sm text-gray-600 mt-2">
            Clique na imagem para alterar seu avatar
        </label>
    </div>
</template>
