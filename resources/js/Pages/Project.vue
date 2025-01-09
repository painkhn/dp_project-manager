<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/Components/main/Header.vue';
import Sidebar from '@/Components/main/Sidebar.vue';
import ProjectInvite from '@/Components/projects/ProjectInvite.vue'
import ProjectUsers from '@/Components/projects/ProjectUsers.vue';
import ProjectInviteList from '@/Components/projects/ProjectInviteList.vue'
import axios from 'axios'
import { Project, ProjectInvitation, User, ProjectUser, Tasks, Report } from '@/types'
import Alert from '@/Components/messages/Alert.vue';
import ErrorAlert from '@/Components/messages/ErrorAlert.vue'; // Импортируем компонент ErrorAlert
import { isValidDate } from '@/Utils/dateValidation'; // Импортируем функцию проверки даты
import ProjectTaskForm from '@/Components/projects/ProjectTaskForm.vue';
import ProjectTaskList from '@/Components/projects/ProjectTaskList.vue';
import Layout from '@/Layouts/MainLayout.vue'

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    project: Project;
    user?: User;
    invitations?: ProjectInvitation[] | null;
    pendingInvitations: ProjectInvitation[] | null;
    projectUsers: ProjectUser[] | null;
    tasks?: Tasks[] | null;
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

const isNewTaskVisible = ref<boolean>(false)
const toggleNewTask = () => {
    isNewTaskVisible.value = !isNewTaskVisible.value
}

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

const form = useForm({
    title: '',
    description: '',
    user_id: '',
    start_date: '',
    end_date: '',
});

const errorMessage = ref('');

// Пример обработки данных формы
const handleSubmit = () => {
    if (!isValidDate(form.start_date)) {
        errorMessage.value = 'Неверный формат даты начала проекта. Используйте формат YYYY-MM-DD.';
        console.log(form.start_date);
        return;
    }

    if (!isValidDate(form.end_date)) {
        errorMessage.value = 'Неверный формат даты окончания проекта. Используйте формат YYYY-MM-DD.';
        console.log(form.end_date);
        return;
    }

    form.post(route('task.store', {id: props.project.id}), {
        onSuccess: () => {
            form.reset();
            isNewTaskVisible.value = false;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>

    <Head :title="props.project.title" />

    <Layout>
        <Alert class="alert-deleted opacity-0 transition-all" :value="'Удалено'" />
        <div class="w-full mx-auto p-5 my-0 rounded-xl flex gap-5">
            <div class="w-2/3">
                <h1 class="text-white font-bold text-4xl mb-5">
                    {{ props.project.title }}
                </h1>
                <p class="text-white/90 font-semibold text-2xl mb-5">
                    {{ props.project.description }}
                </p>
                <p class="text-white/80 mb-5 flex items-center text-2xl gap-3">
                    {{ props.project.start_date }}
                    <span>-</span>
                    {{ props.project.end_date }}
                </p>
                <Link :href="route('profile.index', { id: props.project.user.id })"
                    class="text-white text-2xl mb-5 uppercase transition-all hover:text-white/80 font-bold">
                {{ props.project.user.name }}
                </Link>
                <h2 class="text-white text-4xl font-bold my-5 text-center">
                    Задачи проекта
                </h2>
                <ProjectTaskForm :project="(props.project as Project)"
                    :projectUsers="(props.projectUsers as ProjectUser[] | null)"
                    v-if="$page.props.auth.user.id === props.project.user.id" />
                <ProjectTaskList :tasks="(props.tasks as Tasks[] | null)" :project="(props.project as Project)" />
            </div>
            <div class="w-1/3 text-white border-l border-white/50 pl-5">
                <div v-if="$page.props.auth.user.id == props.project.user.id">
                    <h2 class="text-white text-xl font-bold mb-5">
                        Пригласить пользователя
                    </h2>
                    <ProjectInvite :projectId="props.project.id" @fetch-invited-users="fetchInvitedUsers"
                        class="mb-5" />
                </div>

                <div v-if="$page.props.auth.user.id == props.project.user.id">
                    <h2 class="text-white text-xl my-4 font-bold block mb-5">
                        Приглашены:
                    </h2>

                    <ProjectInviteList :pendingInvitations="props.pendingInvitations" :project="props.project"
                        class="mb-5" />
                </div>

                <h2 class="font-bold text-xl mb-4">
                    Пользователи проекта
                </h2>
                <button @click="toggleProjectUsers" type="button"
                    class="open-btn w-full py-2 border border-white rounded-md transition-all hover:bg-white/10">Открыть</button>
                <ProjectUsers v-if="projectUsersIsVisible" @toggle="toggleProjectUsers"
                    :isVisible="projectUsersIsVisible" :projectUsers="props.projectUsers" :project="props.project" />
            </div>
        </div>
    </Layout>
</template>