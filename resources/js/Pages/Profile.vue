<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/Components/main/Header.vue';
import Sidebar from '@/Components/main/Sidebar.vue';
import AvatarChangeForm from '@/Components/profile/AvatarChangeForm.vue';
import { Project, User, ProjectInvitation } from '@/types'
import axios from 'axios';

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    projects?: Project[];
    user: User;
    invitations?: ProjectInvitation[];
}>();

onMounted(() => {
    initFlowbite();
});

const isSidebarVisible = ref(false);
const isVisibleAvatarChange = ref(false);

const sidebarToggle = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

const avatarChangeIsVisible = async () => {
    isVisibleAvatarChange.value = !isVisibleAvatarChange.value
}

const acceptInvitation = async (invitationId: number) => {
    try {
        const response = await axios.post(`/invitations/${invitationId}/accept`);
    } catch (error) {
        console.error(error);
    }
};

const rejectInvitation = async (invitationId: number) => {
    try {
        await axios.post(`/invitations/${invitationId}/reject`);
        // Обновите список приглашений после отклонения
        // Например, можно удалить приглашение из списка
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>

    <Head :title="props.user.name" />

    <main>
        <div class="w-full flex gap-10 justify-center my-20 relative">
            <div class="max-w-52 w-full text-white bg-white/10 rounded-xl p-5">
                <ul class="flex flex-col gap-2">
                    <li>
                        <div class="avatar cursor-pointer" @click="avatarChangeIsVisible" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                            <img v-if="props.user.avatar == null" src='/img/avatar_default.jpg' class="profileInfo__avatar w-40 h-40 rounded border-slate-200" alt="">
                            <img v-else :src="`/storage/` + props.user.avatar" alt="Аватарка" encType="multipart/form-data" class="profileInfo__avatar w-40 h-40 rounded border-slate-200">
                        </div>
                        <div v-else>
                            <img v-if="props.user.avatar == null" src='/img/avatar_default.jpg' class="profileInfo__avatar w-40 h-40 rounded border-slate-200" alt="">
                            <img v-else :src="`/storage/` + props.user.avatar" alt="Аватарка" encType="multipart/form-data" class="profileInfo__avatar w-40 h-40 rounded border-slate-200">
                        </div>
                        <AvatarChangeForm v-if="isVisibleAvatarChange" :isVisible="isVisibleAvatarChange" @toggle="avatarChangeIsVisible" />
                    </li>
                    <li>
                        <p class="text-white font-bold text-xl">
                            {{ props.user.name }}
                        </p>
                    </li>
                    <li>
                        <p class="text-white font-semibold">
                            {{ props.user.email }}
                        </p>
                    </li>
                    <li>
                        <p>
                            Всего проектов: <span>{{ (props.projects as Project[])?.length }}</span>
                        </p>
                    </li>
                </ul>
            </div>
            <div class="max-w-4xl w-full bg-white/10 rounded-xl p-5">
                <ul class="flex flex-col gap-5" v-if="(props.projects as Project[])?.length > 0">
                    <li>
                        <h2 class="font-bold text-white text-xl" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                            ВАШИ ПРОЕКТЫ
                        </h2>
                        <h2 class="font-bold text-white text-xl" v-else>
                            ПРОЕКТЫ ПОЛЬЗОВАТЕЛЯ {{ ($page.props.user as User).name }}
                        </h2>
                    </li>
                    <li v-for="project in props.projects" :key="project.id">
                        <Link :href="route('project.index', { id: project.id })">
                        <div class="w-full h-auto px-4 py-2 bg-white/10 rounded-md transition-all hover:bg-white/20">
                            <h3 class="text-white font-bold text-xl mb-2">
                                {{ project.title }}
                            </h3>
                            <p class="text-white/90 font-semibold">
                                {{ project.description }}
                            </p>
                        </div>
                        </Link>
                    </li>
                </ul>
                <ul v-else>
                    <h2 class="text-xl text-white text-center" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                        Вы пока не создали ни одной темы
                    </h2>
                    <h2 class="text-xl text-white text-center" v-else>
                        Пользователь {{ ($page.props.user as User).name }} пока не создал ни одной темы
                    </h2>
                </ul>
            </div>
            <div class="max-w-xl w-full h-auto p-5 bg-white/10 rounded-xl" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                <h2 class="font-bold text-white text-xl mb-5">
                    ВАШИ ПРИГЛАШЕНИЯ
                </h2>
                <ul class="flex flex-col gap-2 py-2 rounded-md bg-white/10 px-4" v-if="(props.invitations as ProjectInvitation[])?.length > 0">
                    <li v-for="invitation in props.invitations" :key="invitation.id" class="flex items-center pr-4">
                        <Link :href="route('project.index', { id: invitation.project.id })">
                            <div>
                            <p class="text-white font-semibold">
                                {{ invitation.project.title }}
                            </p>
                            <p class="text-white/90">
                                Приглашение от {{ invitation.inviter.name }}
                            </p>
                        </div>
                        </Link>
                        <button @click="acceptInvitation(invitation.id)" class="ml-auto text-green-500">
                            Принять
                        </button>
                        <button @click="rejectInvitation(invitation.id)" class="text-red-500">
                            Отклонить
                        </button>
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