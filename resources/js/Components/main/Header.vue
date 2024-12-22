<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue'
    import Sidebar from '@/Components/main/Sidebar.vue';
    import { initFlowbite } from 'flowbite'

    defineProps<{
        canLogin?: boolean;
        canRegister?: boolean;
    }>();

    const isSidebarVisible = ref(false);

    const sidebarToggle = () => {
        isSidebarVisible.value = !isSidebarVisible.value;
    };

    onMounted(() => {
        initFlowbite();
    })
</script>

<template>
    <div class="w-full h-28 grid grid-cols-3 items-center">
        <div class="items-center flex h-full">
            <button @click="sidebarToggle" class="absolute left-10 text-white font-bold z-10">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m9 5 7 7-7 7" />
                </svg>
            </button>
        
            <div v-if="isSidebarVisible" @click="sidebarToggle" class="fixed top-0 left-0 w-full h-full bg-black/40 z-[39]">
            </div>
            <Sidebar :isVisible="isSidebarVisible" @toggle="sidebarToggle" />
        </div>
        <div class="justify-self-center">
            <Link :href="route('index')" >
                <img src="/img/logo.svg" alt="" class="transition-all hover:opacity-80 active:opacity-100">
            </Link>
        </div>
    </div>
</template>