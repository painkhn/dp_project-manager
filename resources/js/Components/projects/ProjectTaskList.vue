<script setup lang="ts">
import { Project, Tasks } from '@/types';
import axios from 'axios';
import { ref } from 'vue';
import { format } from 'date-fns';
import TaskReportForm from '../tasks/TaskReportForm.vue';
import TaskReportList from '../tasks/TaskReportList.vue';
import WaitingIcon from '../ui/WaitingIcon.vue';
import CompletedIcon from '../ui/CompletedIcon.vue';
import DeleteIcon from '../ui/DeleteIcon.vue';
import DownArrowIcon from '../ui/DownArrowIcon.vue';
import UpArrowIcon from '../ui/UpArrowIcon.vue';
import CalendarIcon from '../ui/CalendarIcon.vue';

const props = defineProps<{
    tasks: Tasks[] | null;
    project: Project;
}>()

const taskDelete = async (taskId: number) => {
    try {
        await axios.delete(route('task.destroy', { id: taskId }));
        location.reload();
    } catch (error) {
        console.error('Ошибка при удалении задачи:', error);
    }
};

const isTaskDetailsVisible = ref<boolean>(false);
const isTaskReportVisible = ref<boolean>(false);
const btn_id = ref<number>();

const taskDetailsToggle = (taskId: number) => {
    btn_id.value = taskId;
}

const closeDetails = (taskId: number) => {
    btn_id.value = undefined;
}

const taskReportToggle = () => {
    isTaskReportVisible.value = !isTaskReportVisible.value;
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return format(date, 'dd.MM.yyyy');
};
</script>

<template>
    <div v-if="(props.tasks as Tasks[])?.length > 0" class="my-5">
        <ul class="flex flex-col gap-5">
            <li v-for="(task, index) in props.tasks" :key="index">
                <div :class="[
                    'w-full px-4 py-2 border-l-4 transition-all hover:bg-white/10 flex flex-col gap-5',
                    { 'bg-green-900/60': task.status === 'completed' }
                ]"
                    v-if="$page.props.auth.user.id === props.project.user.id || $page.props.auth.user.id === task.user_id">
                    <div class="flex items-center">
                        <p class="text-white text-xl">
                            {{ task.title }}
                        </p>
                        <p class="text-white ml-5" v-if="task.status == 'waiting'">
                            <WaitingIcon />
                        </p>
                        <p class="text-white ml-5" v-else>
                            <CompletedIcon />
                        </p>
                        <p class="text-white text-xl ml-auto">
                            {{ task.user.name }}
                        </p>
                        <button @click="taskDelete(task.id)" class="text-white ml-5"
                            v-if="$page.props.auth.user.id === props.project.user.id">
                            <DeleteIcon />
                        </button>
                        <button class="ml-5"
                            v-if="task.status == 'waiting' || $page.props.auth.user.id === props.project.user.id">
                            <DownArrowIcon @click="taskDetailsToggle(task.id)" v-if="btn_id != task.id"
                                :id="`btn-${task.id}`" />
                            <UpArrowIcon v-if="btn_id == task.id" :id="`btn-${task.id}`"
                                @click="closeDetails(task.id)" />
                        </button>
                    </div>
                    <div v-if="btn_id == task.id">
                        <p class="text-white text-lg mb-2">
                            {{ task.description }}
                        </p>
                        <div class="flex items-center gap-2 mb-5">
                            <CalendarIcon />
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
                        <button @click="taskReportToggle()"
                            class="text-white px-4 py-2 border border-white rounded-md transition-all hover:bg-white/10">
                            Отправить отчёт
                        </button>
                        <TaskReportForm :task="task" :isTaskDetailsVisible="isTaskDetailsVisible"
                            v-if="isTaskReportVisible" />
                        <TaskReportList :task="task" :project="props.project" />
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div v-else>
        <p class="text-white/90 text-2xl text-center">
            Не добавлено ни одной задачи
        </p>
    </div>
</template>