<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, onUnmounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/Components/main/Header.vue';
import Sidebar from '@/Components/main/Sidebar.vue';
import ProfileProjects from '@/Components/profile/ProfileProjects.vue';
import AvatarChangeForm from '@/Components/profile/AvatarChangeForm.vue';
import { Project, User, ProjectInvitation, TeamInvitation, Teams } from '@/types'
import axios from 'axios';
import ProjectTeams from './Project/ProjectTeams.vue';
import ProfileEdit from '@/Components/profile/ProfileEdit.vue';

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    projects?: Project[];
    user: User;
    projectInvitations?: ProjectInvitation[];
    teamInvitations?: TeamInvitation[];
    teams: Teams[] | null;
    userProjects?: Project[];
}>();

const currentDateTime = ref<string>('');
const currentTime = ref<string>('');
const isSidebarVisible = ref(false);
const isVisibleAvatarChange = ref(false);

const updateDateTime = () => {
    const now = new Date();
    currentDateTime.value = now.toLocaleDateString();
}

const updateTime = () => {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0'); // Часы (с лидирующим нулем)
    const minutes = String(now.getMinutes()).padStart(2, '0'); // Минуты (с лидирующим нулем)
    const seconds = String(now.getSeconds()).padStart(2, '0'); // Секунды (с лидирующим нулем)
    currentTime.value = `${hours} : ${minutes} : ${seconds}`; // Форматируем время
};

let intervalId: number | undefined;
const newUserProject = async (projectId: number) => {
    try {
        await axios.post(route('project.user.store', { id: projectId }))
        console.log('норм');
        location.reload()
    } catch (error) {
        console.log('ошибка лол: ', error);
    }
}

const newUserTeam = async (teamId: number) => {
    try {
        const response = await axios.post(route('team.user.store', { id: teamId }))
        console.log('успешно');
        location.reload()
    } catch (error) {
        console.log('ошибка при принятии приглашения в команду: ', error);

    }
}

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

const avatarChangeIsVisible = async () => {
    isVisibleAvatarChange.value = !isVisibleAvatarChange.value
}

const rejectInvitation = async (invitationId: number) => {
    try {
        await axios.post(`/invitations/${invitationId}/reject`);
        // Обновите список приглашений после отклонения
        // Например, можно удалить приглашение из списка
    } catch (error) {
        console.error(error);
    }
};

const isProjects = ref<boolean>(true)
const isTeams = ref<boolean>(false)
const isEdit = ref<boolean>(false)

const projectsVisible = () => {
    isProjects.value = true
    isTeams.value = false
    isEdit.value = false
}

const teamsVisible = () => {
    isTeams.value = true
    isProjects.value = false
    isEdit.value = false
}

const editVisible = () => {
    isEdit.value = true
    isProjects.value = false
    isTeams.value = false
}

onMounted(() => {
    initFlowbite();
    updateDateTime();
    updateTime();
    intervalId = setInterval(updateTime, 1000);
    // console.log(props.userProjects);
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId); // Очищаем интервал, чтобы избежать утечек памяти
    }
});
</script>

<template>

    <Head :title="props.user.name" />

    <Header />

    <main class="">
        <div class="w-full flex gap-10 my-0 relative">
            <div class="max-w-72 main-height w-full text-white border-r border-white/40">
                <ul class="flex flex-col items-center gap-10 max-w-[170px] mx-auto">
                    <li class="">
                        <div class="avatar cursor-pointer" @click="avatarChangeIsVisible"
                            v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                            <img v-if="props.user.avatar == null" src='/img/avatar_default.jpg'
                                class="profileInfo__avatar w-40 h-40 rounded-full border-[3px] border-white" alt="">
                            <img v-else :src="`/storage/` + props.user.avatar" alt="Аватарка"
                                encType="multipart/form-data"
                                class="profileInfo__avatar border-[3px] w-40 h-40 rounded-full border-white">
                        </div>
                        <div v-else>
                            <img v-if="props.user.avatar == null" src='/img/avatar_default.jpg'
                                class="profileInfo__avatar w-40 h-40 rounded-full border-[3px] border-white" alt="">
                            <img v-else :src="`/storage/` + props.user.avatar" alt="Аватарка"
                                encType="multipart/form-data"
                                class="profileInfo__avatar w-40 h-40 border-[3px] rounded-full border-white">
                        </div>
                        <AvatarChangeForm v-if="isVisibleAvatarChange" :isVisible="isVisibleAvatarChange"
                            @toggle="avatarChangeIsVisible" />
                    </li>
                    <li class="flex flex-col gap-2 text-center">
                        <p class="text-white font-bold text-2xl uppercase">
                            {{ props.user.name }}
                        </p>
                        <p class="text-white/80">
                            {{ props.user.email }}
                        </p>
                        <p>
                            Всего проектов: <span>{{ (props.projects as Project[])?.length }}</span>
                        </p>
                    </li>
                    <li class="flex flex-col gap-4">
                        <p class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z" />
                            </svg>
                            Профиль
                        </p>
                        <p class="flex items-center gap-4 cursor-pointer" @click="projectsVisible">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6h8m-8 6h8m-8 6h8M4 16a2 2 0 1 1 3.321 1.5L4 20h5M4 5l2-1v6m-2 0h4" />
                            </svg>
                            Список проектов
                        </p>
                        <p class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                            </svg>
                            Расписание
                        </p>
                        <p class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
                            </svg>
                            Аналитика
                        </p>
                        <p class="flex items-center gap-4 cursor-pointer" @click="editVisible">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                            </svg>
                            Редактировать
                        </p>
                        <p class="flex items-center gap-4 cursor-pointer" @click="teamsVisible">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                            </svg>
                            Команда
                        </p>
                    </li>
                </ul>
            </div>
            <div class="max-w-4xl w-full rounded-xl">
                <p class="text-white text-4xl mb-2.5">
                    {{ currentTime }}
                </p>
                <p class="text-white text-lg text-white/90 mb-5">
                    {{ currentDateTime }}
                </p>
                <ProfileProjects :projects="(props.projects as Project[])"
                    :user-projects="(props.userProjects as Project[])" :user="(props.user as User)" v-if="isProjects" />
                <ProjectTeams :teams="(props.teams as Teams[])" v-if="isTeams" :user="(props.user as User)" />
                <ProfileEdit :user="(props.user as User)" v-if="isEdit" />
            </div>
            <div class="max-w-xl w-full ml-auto h-auto px-10 text-center border-l border-white/50"
                v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                <h2 class="font-bold text-white text-xl mb-5">
                    ВАШИ ПРИГЛАШЕНИЯ
                </h2>
                <ul class="flex flex-col gap-2 py-2 text-left rounded-md bg-white/10 px-4"
                    v-if="(props.projectInvitations as ProjectInvitation[])?.length > 0 && (props.projectInvitations as ProjectInvitation[]).some(invitation => invitation.status === 'pending') || (props.teamInvitations as TeamInvitation[])?.length > 0 && (props.teamInvitations as TeamInvitation[]).some(invitation => invitation.status === 'pending')">
                    <li v-for="invitation in (props.projectInvitations as ProjectInvitation[])" :key="invitation.id"
                        class="flex items-center pr-4">
                        <div class="flex justify-between w-full items-center">
                            <div class="w-9/12 text-nowrap overflow-hidden">
                                <Link :href="route('project.index', { id: invitation.project.id })">
                                <p class="text-white font-semibold">
                                    {{ invitation.project.title }}
                                </p>
                                </Link>
                                <p class="text-white/90">
                                    Приглашение от
                                    <Link :href="route('profile.index', { id: invitation.inviter.id })">{{
                                        invitation.inviter.name }}</Link>
                                </p>
                            </div>
                            <div class="flex items-center gap-7">
                                <button @click="newUserProject(invitation.project.id)" class="ml-auto">
                                    <svg class="w-6 h-6 text-green-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                </button>
                                <button @click="rejectInvitation(invitation.id)">
                                    <svg class="w-6 h-6 text-red-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li v-for="invitation in (props.teamInvitations as TeamInvitation[])" :key="invitation.id"
                        class="flex items-center pr-4">
                        <div class="flex justify-between w-full items-center">
                            <div>
                                <Link :href="route('team.page', { id: invitation.team.id })">
                                <p class="text-white font-semibold">
                                    {{ invitation.team.title }}
                                </p>
                                </Link>
                                <p class="text-white/90">
                                    Приглашение от
                                    <Link :href="route('profile.index', { id: invitation.inviter.id })">{{
                                        invitation.inviter.name }}</Link>
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button @click="newUserTeam(invitation.team.id)" class="ml-auto text-green-500">
                                    Принять
                                </button>
                                <button @click="rejectInvitation(invitation.id)" class="text-red-500">
                                    Отклонить
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul v-else>
                    <h2 class="text-xl text-white text-center">
                        У вас нет приглашений
                    </h2>
                </ul>
            </div>
        </div>
    </main>
</template>

<style scoped>
.main-height {
    min-height: calc(100vh - 224px);
}
</style>