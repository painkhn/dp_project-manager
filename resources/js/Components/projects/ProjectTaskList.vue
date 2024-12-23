<script setup lang="ts">
import { Project, Tasks } from '@/types';
import axios from 'axios';
import { defineProps } from 'vue';

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
</script>

<template>
    <div v-if="(props.tasks as Tasks[])?.length > 0" class="my-5">
        <ul class="flex flex-col gap-5">
            <li v-for="(task, index) in props.tasks" :key="index">
                <div class="w-full px-4 py-2 border-l-4 transition-all hover:bg-white/10 flex" v-if="task.user.id === props.project.user.id || $page.props.auth.user.id === props.project.user.id">
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
</template>