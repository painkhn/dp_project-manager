<script setup lang="ts">
import { Project, Tasks } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const props = defineProps<{
    task: Tasks;
    // isTaskDetailsVisible: boolean;
}>()

const isTaskDetailsVisible = ref<boolean[]>([]);

const form = useForm({
    message: '',
    file: '',
});

const handleSubmit = (taskId: number) => {
    form.post(route('report.store', { taskId: taskId }), {
        onSuccess: () => {
            form.reset();
            isTaskDetailsVisible.value[taskId] = false;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>
    <form @submit.prevent="handleSubmit(task.id)" class="my-5 flex items-center gap-3">
        <input type="text" v-model="form.message"
            class="bg-transparent rounded-t-md border-x-0 border-t-0 border-b-white focus:bg-white/10 focus:!border-0 focus:!border-b focus:dark:!border-b-white focus:ring-0 dark:text-white">
        <button type="submit" class="">
            <svg class="w-6 h-6 text-gray-800 dark:text-white hover:opacity-80 transition-all" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </button>
    </form>
</template>