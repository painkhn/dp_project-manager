<script setup lang="ts">
import { defineProps, onMounted, ref } from 'vue';
import { Project, Report, Tasks } from '@/types';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import TaskBackModal from '@/Components/tasks/TaskBackModal.vue'

const props = defineProps<{
    task: Tasks;
    project: Project;
}>();

const isBackVisible = ref<boolean>(false);

const backToggle = () => {
    isBackVisible.value = !isBackVisible.value;
}

onMounted(() => {
    console.log(props.task.report);
})
</script>

<template>
    <div>
        <h2 class="text-white font-bold text-lg mb-2">
            История отчётов:
        </h2>
        <ul class="flex flex-col gap-3" v-if="(props.task.report as Report[])?.length > 0">
            <li v-for="(report, index) in props.task.report" :key="index"
                class="px-4 py-2 border-l-2 border-white flex justify-between">
                <div class="flex items-center gap-3">
                    <p class="dark:text-white">
                        {{ report.message }}
                    </p>
                    <p class="dark:text-white">
                        |
                    </p>
                    <p class="dark:text-white uppercase">
                        {{ report.user.name }}
                        <span v-if="props.project.user.id === report.user.id">
                            (Администратор)
                        </span>
                    </p>
                </div>
                <div v-if="props.project.user.id === $page.props.auth.user.id">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white cursor-pointer" aria-hidden="true"
                        @click="backToggle" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </li>
        </ul>
        <p v-else class="text-white/80 pb-5">
            Не отправлено ни одного отчёта
        </p>
    </div>
    <TaskBackModal :task="props.task" v-if="isBackVisible" />
</template>