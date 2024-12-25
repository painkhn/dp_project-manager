<script setup lang="ts">
import { Project, Tasks } from '@/types';
import axios from 'axios';
import { defineProps, handleError, ref } from 'vue';
import { format } from 'date-fns';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    tasks: Tasks[] | null;
    project: Project;
}>()

const taskDelete = async (taskId: number) => {
    try {
        await axios.delete(route('task.destroy', { id: taskId }));
        location.reload(); // Перезагрузка страницы
    } catch (error) {
        console.error('Ошибка при удалении задачи:', error);
    }
};

// Создаем массив флагов для каждой задачи
const isTaskDetailsVisible = ref<boolean[]>([]);

// Функция для переключения видимости деталей задачи
const taskDetailsToggle = (index: number) => {
    isTaskDetailsVisible.value = isTaskDetailsVisible.value.map((visible, i) => 
        i === index ? !visible : visible
    );
}

// Инициализируем массив флагов в зависимости от количества задач
if (props.tasks) {
    isTaskDetailsVisible.value = new Array(props.tasks.length).fill(false);
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString); // Преобразуем строку в объект Date
    return format(date, 'dd.MM.yyyy'); // Возвращаем дату в формате "дд.мм.гггг"
};

const form = useForm({
    message: '',
    file: '',
});

const handleSubmit = (taskId: number) => {

    form.post(route('report.store', {taskId: taskId}), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>
    <div v-if="(props.tasks as Tasks[])?.length > 0" class="my-5">
        <ul class="flex flex-col gap-5">
            <li v-for="(task, index) in props.tasks" :key="index">
                <div class="w-full px-4 py-2 border-l-4 transition-all hover:bg-white/10 flex flex-col gap-5" v-if="task.user.id === props.project.user.id || $page.props.auth.user.id === props.project.user.id">
                    <div class="flex items-center">
                        <p class="text-white text-xl">
                            {{ task.title }}
                        </p>
                        <p class="text-white ml-5" v-if="task.status == 'waiting'">
                            <svg  class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>                              
                        </p>
                        <p class="text-white ml-5" v-else>
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>                
                        </p>
                        <p class="text-white text-xl ml-auto">
                            {{ task.user.name }}
                        </p>
                        <button @click="taskDelete(task.id)" class="text-white ml-5">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                            </svg>                                      
                        </button>
                        <button class="ml-5" @click="taskDetailsToggle(index)">
                            <svg v-if="!isTaskDetailsVisible[index]" class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                            </svg>
                            <svg v-else class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/>
                            </svg>                                                      
                        </button>
                    </div>
                    <div v-if="isTaskDetailsVisible[index]">
                        <p class="text-white text-lg mb-2">
                            {{ task.description }}
                        </p>
                        <div class="flex items-center gap-2 mb-5">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                            </svg>
                            <p class="text-white text-lg">
                                {{ formatDate(task.start_date) }}
                            </p>
                            <span class="text-white">
                                -
                            </span>
                            <p class="text-white text-lg">
                                {{ formatDate(task.end_date) }}
                            </p>
                        </div>
                        <button class="text-white px-4 py-2 border border-white rounded-md transition-all hover:bg-white/10">
                            Отправить отчёт
                        </button>
                        <!-- <form @submit.prevent="handleSubmit(task.id)">
                            <input type="text" v-model="form.message">
                            <button type="submit">
                                фывафыв
                            </button>
                        </form> -->
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>