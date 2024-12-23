<script setup lang="ts">
import ErrorAlert from '@/Components/messages/ErrorAlert.vue'; // Импортируем компонент ErrorAlert
import { isValidDate } from '@/Utils/dateValidation'; // Импортируем функцию проверки даты
import { useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import { Project, ProjectUser } from '@/types'
import axios from 'axios';

const props = defineProps<{
    project: Project;
    projectUsers: ProjectUser[] | null;
}>()

const form = useForm({
    title: '',
    description: '',
    user_id: '',
    start_date: '',
    end_date: '',
});

const isNewTaskVisible = ref<boolean>(false)
const toggleNewTask = () => {
    isNewTaskVisible.value = !isNewTaskVisible.value
}

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
    <div>
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
</template>