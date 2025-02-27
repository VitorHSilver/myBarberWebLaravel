<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref } from "vue";

const isSmallScreen = ref(false);

function updateScreenSize() {
    isSmallScreen.value = window.innerWidth < 768;
}
onMounted(() => {
    updateScreenSize();
    window.addEventListener("resize", updateScreenSize);
});
onUnmounted(() => {
    window.removeEventListener("resize", updateScreenSize);
});
</script>

<template>
    <div class="md:grid grid-cols-2">
        <div v-if="!isSmallScreen" class=" flex items-center">
            <img src="/barber-high-resolution-logo-transparent.svg" alt="" />
        </div>
        
        
        <div
            class="flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0 bg-white"
        >
            <div>
                <Link href="/">
                    <ApplicationLogo
                        class="h-20 w-20 fill-current text-gray-500"
                    />
                </Link>
            </div>

            <div
                class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-lg sm:max-w-md sm:rounded-lg"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
