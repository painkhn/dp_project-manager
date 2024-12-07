<!-- Pages/Welcome.vue -->
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/Components/main/Header.vue';
import Sidebar from '@/Components/main/Sidebar.vue';

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
    <Head title="Welcome" />

    <!-- <Header /> -->

    <button @click="sidebarToggle" class="absolute bottom-10 left-10 text-white font-bold z-10">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
        </svg>
    </button>

    <div v-if="isSidebarVisible" @click="sidebarToggle" class="fixed top-0 left-0 w-full h-full bg-black/40 z-[39]"></div>
    <Sidebar :isVisible="isSidebarVisible" @toggle="sidebarToggle" />
</template>