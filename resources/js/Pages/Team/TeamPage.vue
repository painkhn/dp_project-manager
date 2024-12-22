<script setup lang="ts">
import { defineProps, onMounted, ref } from 'vue';
import { User, Team, TeamUsers } from '@/types'
import { initFlowbite } from 'flowbite';
import Sidebar from '@/Components/main/Sidebar.vue';
import { Link, Head } from '@inertiajs/vue3';
import InviteToTeam from '@/Components/teams/InviteToTeam.vue';
import Header from '@/Components/main/Header.vue';

const props = defineProps<{
    user: User;
    team: Team;
    teamUsers: TeamUsers[];
}>()

onMounted(() => {
    console.log(props.teamUsers);
    initFlowbite();
})

const isSidebarVisible = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};
</script>

<template>
    <Head :title="props.team.title" />

    <Header />

    <div class="max-w-4xl w-full p-5 bg-white/10 rounded-md mx-auto my-0">
        <h1 class="text-white text-center text-2xl font-bold mb-3">
            {{ props.team.title }}
        </h1>
        <p class="text-center text-white/90 w-10/12 mx-auto my-0 mb-3">
            {{ props.team.description }}
        </p>
        <div class="text-center">
            <!-- <Link :href="route('profile.index', { id: props.team.user_id })" class="text-center text-white/90 inline">
                aawe
            </Link> -->
            создатель
        </div>
        <h2 class="text-white text-lg font-semibold mb-5">
            Пользователи команды:
        </h2>
        <ul class="w-full flex flex-col mb-5">
            <li v-for="(user, index) in props.teamUsers" :key="index" class="w-full flex">
                <!-- <Link :href="route('profile.index', { id: props.user.id })" class="text-lg rounded-md transition-all text-white px-4 py-2 bg-white/10 hover:bg-white/15 w-full">
                    {{ user.name }}
                </Link> -->
                <div class="px-4 py-2 bg-white/10 w-full rounded-md">
                    <Link :href="route('profile.index', { id: user.user.id })" class="text-white transition-all hover:underline font-semibold">
                        {{ user.user.name }}
                    </Link>
                </div>
            </li>
        </ul>
        <h2 class="text-white text=lg font-semibold mb-5">
            Пригласить пользователей
        </h2>
        <InviteToTeam :teamId="props.team.id" />
    </div>
</template>