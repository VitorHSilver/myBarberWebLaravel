<script setup lang="ts">
import Toast from "primevue/toast";
</script>
<template>
    <Toast
        position="top-left"
        :breakpoints="{
            '640px': { width: '100%', margin: '0 auto' },
            '768px': { width: '50%' },
        }"
    />
</template>
