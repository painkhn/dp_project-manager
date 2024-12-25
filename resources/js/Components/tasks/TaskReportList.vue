<script setup lang="ts">
import { defineProps, onMounted } from 'vue';
import { Report, Tasks } from '@/types';

const props = defineProps<{
    task: Tasks;
}>();

onMounted(() => {
    console.log(props.task.report);
})
</script>

<template>
    <div>
        <h2 class="text-white font-bold text-lg my-5">
            История отчётов: 
        </h2>
        <ul class="flex flex-col gap-3">
            <li v-for="(report, index) in props.task.report" :key="index" class="px-4 py-2 bg-white/10 flex justify-between">
                <p class="text-white">
                    {{ report.message }}
                </p>
                <div>
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>                      
                </div>
            </li>
        </ul>
    </div>
</template>