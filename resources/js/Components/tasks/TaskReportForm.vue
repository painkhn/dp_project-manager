<script setup lang="ts">
import { Project, Tasks } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'

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
    <form @submit.prevent="handleSubmit(task.id)" class="my-5 flex flex-col gap-4">
        <div class="flex flex-col gap-1">
            <Label class="text-white/70 font-normal">Отчёт</Label>
            <Input type="text" v-model="form.message"
                class="w-full bg-transparent focus:!border-white dark:text-white" />
        </div>
        <Button type="submit" class="">
            Отправить
        </Button>
    </form>
</template>