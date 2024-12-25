<script setup lang="ts">
import { Tasks } from '@/types';
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
    form.post(route('report.store', {taskId: taskId}), {
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
    <form @submit.prevent="handleSubmit(task.id)">
        <input type="text" v-model="form.message">
        <button type="submit">
            фывафыв
        </button>
    </form>    
</template>