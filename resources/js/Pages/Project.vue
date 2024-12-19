<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/Components/main/Header.vue';
import Sidebar from '@/Components/main/Sidebar.vue';
import ProjectInvite from '@/Components/projects/ProjectInvite.vue'
import ProjectUsers from '@/Components/projects/ProjectUsers.vue';
import ProjectInviteList from '@/Components/projects/ProjectInviteList.vue'
import axios from 'axios'
import { Project, ProjectInvitation, User, ProjectUser } from '@/types'
import Alert from '@/Components/messages/Alert.vue';

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    project: Project;
    user?: User;
    invitations?: ProjectInvitation[] | null;
    pendingInvitations: ProjectInvitation[] | null;
    projectUsers: ProjectUser[] | null;
}>();

const getAlert = () => {
    const alert = document.querySelector('.alert-deleted')
    alert?.classList.add('!opacity-100')
    setTimeout(function() {
        alert?.classList.remove('!opacity-100')
    }, 2000)
}

const invitedUsers = ref<{ id: number; name: string }[]>([]);
const projectUsersIsVisible = ref(false);

const toggleProjectUsers = async () => {
    const openBtn = document.querySelector('.open-btn')
    projectUsersIsVisible.value = !projectUsersIsVisible.value
    openBtn?.classList.toggle('hidden')
}

const fetchInvitedUsers = async (projectId: number) => {
    try {
        const response = await axios.get(`/project/${projectId}/invited_users`)
        invitedUsers.value = response.data
        // console.log('Приглашенные пользователи:', response.data);
        invitedUsers.value = response.data.map((user: { id: number }) => user.id)
    } catch (error) {
        console.error(error)
    }
}

// const invitationId = (props.invitation as ProjectInvitation[]).id
// const invitationId = (props.invitation as ProjectInvitation) ? (props.invitation as ProjectInvitation).id : null

onMounted(() => {
    initFlowbite();
    const projectId = props.project.id;
    fetchInvitedUsers(projectId);
    console.log(props.projectUsers);
});

const isSidebarVisible = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};
</script>

<template>
    <Head :title="props.project.title" />

    <main>
        <Alert class="alert-deleted opacity-0 transition-all" :value="'Удалено'" />
        <div class="max-w-4xl mx-auto p-5 my-20 bg-white/10 rounded-xl flex">
            <div class="w-1/2">
                    <h1 class="text-white font-bold text-2xl mb-5">
                    {{ props.project.title }}
                </h1>
                <p class="text-white/90 font-semibold mb-5">
                    {{ props.project.description }}
                </p>
                <p class="text-white/80 mb-5 flex items-center gap-3">
                    {{ props.project.start_date }}
                    <span>-</span>
                    {{ props.project.end_date }}
                </p>
                <Link :href="route('profile.index', { id: props.project.user.id })" class="text-white/80 mb-5">
                    {{ props.project.user.name }}
                </Link>
                <ProjectInvite :projectId="props.project.id" @fetch-invited-users="fetchInvitedUsers" class="mb-5" v-if="$page.props.auth.user.id == props.project.user.id" />
                <h2 class="text-white text-xl mb-4 font-bold">
                    Приглашены:
                </h2>
                <!-- <ul class="flex flex-col gap-2 py-2 rounded-b-md bg-white/10" v-if="props.pendingInvitations && props.pendingInvitations.length > 0">
                    <li v-for="invitation in props.pendingInvitations" :key="invitation.id" class="flex items-center pr-4">
                        <Link :href="route('profile.index', { id: invitation.invitee?.id })"
                            class="text-white font-semibold w-full h-full block px-4 py-2 hover:bg-white/10 rounded-b-md">
                            {{ invitation.invitee?.name }}
                        </Link>
                        <button @click="deleteInvitation(invitation.id)" v-if="$page.props.auth.user.id == props.project.user.id">
                            <svg class="w-5 h-5 text-white transition-all hover:scale-125" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" 
                                />
                            </svg>
                        </button>
                    </li>
                </ul> -->
                <ProjectInviteList :pendingInvitations="props.pendingInvitations" :project="props.project" />
            </div>
            <div class="w-1/2 text-white">
                <h2 class="font-bold text-xl mb-4">
                    Пользователи проекта
                </h2>
                <button @click="toggleProjectUsers" type="button" class="open-btn w-full py-2 border border-white rounded-md transition-all hover:bg-white/10">Открыть</button>
                <ProjectUsers v-if="projectUsersIsVisible" @toggle="toggleProjectUsers" :isVisible="projectUsersIsVisible" :projectUsers="props.projectUsers" :project="props.project" />
            </div>
        </div>
    </main>

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