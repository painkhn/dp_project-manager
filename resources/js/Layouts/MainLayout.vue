<script setup lang="ts">
import Header from '@/Components/main/Header.vue'
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import Sidebar from '@/Components/main/Sidebar.vue';
import Layout from '@/Layouts/MainLayout.vue'

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

onMounted(() => {
    initFlowbite();
});

const isSidebarVisible = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};
</script>

<template>
    <Header />

    <main>
        <slot />
    </main>
</template>