<script setup lang="ts">
import { defineProps, onMounted, ref } from 'vue';
import { Report, Tasks } from '@/types';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps<{
    task: Tasks;
}>();

const returnTaskBack = async (taskId: number) => {
    try {
        const response = await axios.patch(route('report.back', { taskId: taskId }));
        console.log('Задача возвращена');
        location.reload()
    } catch (error) {
        console.log(error);

    }
}

onMounted(() => {
    console.log(props.task.report);
})
</script>

<template>
    <div>
        <h2 class="text-white font-bold text-lg my-5">
            История отчётов:
        </h2>
        <ul class="flex flex-col gap-3" v-if="(props.task.report as Report[])?.length > 0">
            <li v-for="(report, index) in props.task.report" :key="index"
                class="px-4 py-2 border-l-2 border-white flex justify-between">
                <p class="text-white">
                    {{ report.message }}
                </p>
                <div>
                    <svg class="w-6 h-6 text-gray-800 dark:text-white cursor-pointer" aria-hidden="true"
                        @click="returnTaskBack(task.id)" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
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
</template>