<!-- Pages/Welcome.vue -->
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/Components/main/Header.vue';
import Sidebar from '@/Components/main/Sidebar.vue';
import Layout from '@/Layouts/MainLayout.vue'
import { FwbDropdown, FwbListGroup, FwbListGroupItem } from 'flowbite-vue'

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});

const isSidebarVisible = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};
</script>

<template>

    <Head title="Главная страница" />

    <Layout></Layout>
</template>