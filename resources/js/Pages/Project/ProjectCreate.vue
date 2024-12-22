<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import { initFlowbite } from 'flowbite';
    import Header from '@/Components/main/Header.vue';
    import Sidebar from '@/Components/main/Sidebar.vue';
    import ErrorAlert from '@/Components/messages/ErrorAlert.vue'; // Импортируем компонент ErrorAlert
    import { isValidDate } from '@/Utils/dateValidation'; // Импортируем функцию проверки даты

    const props = defineProps<{
        canLogin?: boolean;
        canRegister?: boolean;
    }>();

    onMounted(() => {
        initFlowbite();
    });

    const isSidebarVisible = ref(false);

    const sidebarToggle = () => {
        isSidebarVisible.value = !isSidebarVisible.value;
    };

    const form = useForm({
        title: '',
        description: '',
        start_date: '',
        end_date: '',
    });

    const errorMessage = ref('');

    // Пример обработки данных формы
    const handleSubmit = () => {
        if (!isValidDate(form.start_date)) {
            errorMessage.value = 'Неверный формат даты начала проекта. Используйте формат YYYY-MM-DD.';
            console.log(form.start_date);
            return;
        }

        if (!isValidDate(form.end_date)) {
            errorMessage.value = 'Неверный формат даты окончания проекта. Используйте формат YYYY-MM-DD.';
            console.log(form.end_date);
            return;
        }

        form.post(route('project.store'), {
            onSuccess: () => {
                form.reset();
            },
            onError: (errors) => {
                errorMessage.value = Object.values(errors).join('\n');
                console.log(errorMessage);
            },
        });
    };
</script>

<template>
    <Head title="Создать проект" />

    <Header />

    <main>
        <div class="w-full my-5">
            <div class="max-w-xl w-full mx-auto my-0 h-auto p-5 bg-white/5 rounded-xl">
                <form @submit.prevent="handleSubmit" class="text-white flex flex-col gap-5">
                    <ErrorAlert :message="errorMessage" @close="errorMessage = ''" />
                    <div class="flex flex-col gap-2">
                        <label for="">Название проекта</label>
                        <input type="text" v-model="form.title" class="!border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="">Описание проекта</label>
                        <textarea type="text" v-model="form.description" class="!border-0 !border-b px-2 py-2 h-40 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent"></textarea>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="">Дедлайн</label>
                        <div class="flex items-center gap-5">
                            <input type="text" v-date-mask v-model="form.start_date" placeholder="Начало проекта" class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                            <input type="text" v-date-mask v-model="form.end_date" placeholder="Конец проекта" class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Отправить</button>
                </form>
            </div>
        </div>
    </main>
</template>