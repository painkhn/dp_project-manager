<script setup lang="ts">
import Sidebar from '@/Components/main/Sidebar.vue';
import { ref, defineProps, onMounted } from 'vue';
import { User, Teams } from '@/types'
import { initFlowbite } from 'flowbite';
import { Head, Link } from '@inertiajs/vue3';
import Header from '@/Components/main/Header.vue';

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

    <Header />
    
    <div class="max-w-4xl w-full mx-auto my-0 bg-white/10 rounded-md p-5">
        <ul v-if="(props.teams as Teams[])?.length > 0">
            <li>
                <h2 class="text-white text-xl font-bold mb-5">
                    Ваша команда
                </h2>
            </li>
            <ul>
                <li v-for="(team, index) in props.teams" :key="team.id" class="text-white">
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
</template>