<script setup lang="ts">
import Sidebar from '@/Components/main/Sidebar.vue';
import { ref, defineProps, onMounted } from 'vue';
import { User, Teams } from '@/types'
import { initFlowbite } from 'flowbite';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    user: User;
    teams: Teams[] | null;
}>()

const isSidebarVisible = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

onMounted(() => {
    initFlowbite();
});
</script>

<template>
    <Head title="Список команд" />
    
    <div class="max-w-4xl w-full mx-auto my-20 bg-white/10 rounded-md p-5">
        <ul v-if="(props.teams as Teams[])?.length > 0">
            <li>
                <h2 class="text-white text-xl font-bold mb-5">
                    Ваши команды
                </h2>
            </li>
            <ul class="grid grid-cols-2 gap-5">
                <li v-for="(team, index) in props.teams" :key="team.id" class="text-white">
                    <!-- <p>
                        {{ team.title }}
                    </p> -->
                    <Link :href="route('team.page', { id: team.id })">
                        <div class="w-full py-2 bg-white/10 px-4 rounded-md transition-all hover:bg-white/15">
                            {{ team.title }}
                        </div>
                    </Link>
                </li>
            </ul>
        </ul>
        <p v-else class="text-white/80 text-xl">
            Вы не состоите ни в одной команде
        </p>
        <Link :href="route('team.create')" class="text-white mt-5 block">
            <button type="button" class="w-full py-2 bg-transparent transition-all border border-white rounded-md hover:bg-white/10">
                Создать команду
            </button>
        </Link>
    </div>

    <button @click="sidebarToggle" class="absolute bottom-10 left-10 text-white font-bold z-10">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m9 5 7 7-7 7" />
        </svg>
    </button>

    <div v-if="isSidebarVisible" @click="sidebarToggle" class="fixed top-0 left-0 w-full h-full bg-black/40 z-[39]">
    </div>
    <Sidebar :isVisible="isSidebarVisible" @toggle="sidebarToggle" />
</template>