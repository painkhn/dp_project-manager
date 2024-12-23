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
import { Project, ProjectInvitation, User, ProjectUser, Tasks } from '@/types'
import Alert from '@/Components/messages/Alert.vue';
import ErrorAlert from '@/Components/messages/ErrorAlert.vue'; // Импортируем компонент ErrorAlert
import { isValidDate } from '@/Utils/dateValidation'; // Импортируем функцию проверки даты

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

const newTask = async () => {
    const response = await axios.post(route(''), { id: props.project.id })
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

    const taskDelete = async (taskId: number) => {
        try {
            await axios.delete(route('task.destroy', { id: taskId }));
            // После успешного удаления можно обновить список пользователей
            location.reload(); // Перезагрузка страницы
        } catch (error) {
            console.error('Ошибка при удалении пользователя:', error);
        }
    };
</script>

<template>
    <Head :title="props.project.title" />

    <Header />

    <main>
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
                <Link :href="route('profile.index', { id: props.project.user.id })" class="text-white text-2xl mb-5 uppercase transition-all hover:text-white/80 font-bold">
                    {{ props.project.user.name }}
                </Link>
                <h2 class="text-white text-4xl font-bold my-5 text-center">
                    Задачи проекта
                </h2>
                <div v-if="$page.props.auth.user.id === props.project.user.id">
                    <button @click="toggleNewTask" class="w-full flex justify-center py-2 border border-white rounded-md transition-all hover:bg-white/10">
                        <svg v-if="!isNewTaskVisible" class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <svg v-else class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                    <form @submit.prevent="handleSubmit" action="" v-if="isNewTaskVisible" class="flex flex-col gap-5 mt-5">
                        <div class="text-white flex flex-col text-lg gap-2">
                            <label>
                                Название задачи
                            </label>
                            <input v-model="form.title" type="text" class="bg-transparent focus:border-white rounded-t-md border-x-0 border-t-0 focus:!border-x-0 focus:!border-t-0 focus:ring-0 border-b-white focus:bg-white/10">
                        </div>
                        <div class="text-white flex flex-col text-lg gap-2">
                            <label>
                                Описание задачи
                            </label>
                            <textarea v-model="form.description" type="text" class="bg-transparent focus:border-white min-h-24 rounded-t-md border-x-0 border-t-0 focus:!border-x-0 focus:!border-t-0 focus:ring-0 border-b-white focus:bg-white/10"></textarea>
                        </div>
                        <div class="text-white flex flex-col text-lg gap-2">
                            <label>
                                Ответственный
                            </label>
                            <select v-model="form.user_id" name="" id="" class="bg-transparent focus:border-white rounded-t-md border-x-0 border-t-0 focus:!border-x-0 focus:!border-t-0 focus:ring-0 border-b-white focus:bg-white/10">
                                <option selected class="text-black">Не назначено</option>
                                <option :value="user.user.id" v-for="(user, index) in (props.projectUsers as ProjectUser[] | undefined)" :key="index" class="text-black">{{ user.user.name }}</option>
                            </select>
                        </div>
                        <div class="text-white flex flex-col text-lg gap-2">
                            <label for="">Дедлайн</label>
                            <div class="flex items-center gap-5">
                                <input type="text" v-date-mask v-model="form.start_date" placeholder="Начало задачи" class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                                <input type="text" v-date-mask v-model="form.end_date" placeholder="Конец задачи" class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2 text-white bg-transparent border border-white rounded-md transition-all hover:bg-white/10">
                            Создать
                        </button>
                    </form>
                </div>
                <div v-if="(props.tasks as Tasks[])?.length > 0" class="my-5">
                    <ul class="flex flex-col gap-5">
                        <li v-for="(task, index) in props.tasks" :key="index">
                            <div class="w-full px-4 py-2 border-l-4 transition-all hover:bg-white/10 flex">
                                <p class="text-white text-xl">
                                    {{ task.title }}
                                </p>
                                <p class="text-white text-xl ml-auto">
                                    {{ task.user.name }}
                                </p>
                                <button @click="taskDelete(task.id)" class="text-white ml-5">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>                                      
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-1/3 text-white border-l border-white/50 pl-5">
                <div v-if="$page.props.auth.user.id == props.project.user.id">
                    <h2 class="text-white text-xl font-bold mb-5">
                        Пригласить пользователя
                    </h2>
                    <ProjectInvite :projectId="props.project.id" @fetch-invited-users="fetchInvitedUsers" class="mb-5"  />
                </div>
                
                <div v-if="$page.props.auth.user.id == props.project.user.id">
                    <h2 class="text-white text-xl my-4 font-bold block mb-5">
                        Приглашены:
                    </h2>
    
                    <ProjectInviteList :pendingInvitations="props.pendingInvitations" :project="props.project" class="mb-5" />
                </div>

                <h2 class="font-bold text-xl mb-4">
                    Пользователи проекта
                </h2>
                <button @click="toggleProjectUsers" type="button" class="open-btn w-full py-2 border border-white rounded-md transition-all hover:bg-white/10">Открыть</button>    
                <ProjectUsers v-if="projectUsersIsVisible" @toggle="toggleProjectUsers" :isVisible="projectUsersIsVisible" :projectUsers="props.projectUsers" :project="props.project" />
            </div>
        </div>
    </main>
</template>