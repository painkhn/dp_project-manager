<script setup lang="ts">
import { defineProps } from 'vue';
import { Report, Tasks } from '@/types'
const props = defineProps<{
    task: Tasks
}>();
</script>

<template>
    <div>
        <h2 class="text-white font-bold text-lg my-5">
            История отчётов: 
        </h2>
        <ul class="flex flex-col gap-3">
            <li v-for="(report, index) in (props.task.report as Tasks)" :key="index">
                <p class="text-white">
                    {{ report?.message }}
                </p>
            </li>
        </ul>
    </div>
</template>