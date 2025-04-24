<script setup lang="ts">
import ErrorAlert from '@/Components/messages/ErrorAlert.vue'; // Импортируем компонент ErrorAlert
import { isValidDate } from '@/Utils/dateValidation'; // Импортируем функцию проверки даты
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, defineProps, computed } from 'vue';
import { Project, ProjectUser, User } from '@/types'
import axios from 'axios';

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select'

const currentUser = computed<User>(() => usePage().props.auth.user)

// Проверяем, есть ли пользователи в проекте
const hasProjectUsers = computed(() => {
    return (props.projectUsers as ProjectUser[])?.length > 0
})

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/Components/ui/dialog'
import Input from '../ui/input/Input.vue';
import Textarea from '../ui/textarea/Textarea.vue';

const props = defineProps<{
    project: Project;
    projectUsers: ProjectUser[] | null;
    user: User;
}>()

const form = useForm({
    title: '',
    description: '',
    user_id: '',
    start_date: '',
    end_date: '',
});

const isNewTaskVisible = ref<boolean>(false)
// const toggleNewTask = () => {
//     isNewTaskVisible.value = !isNewTaskVisible.value
// }

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

    form.post(route('task.store', { id: props.project.id }), {
        onSuccess: () => {
            form.reset();
            isNewTaskVisible.value = false;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>
    <div>

        <Dialog>
            <DialogTrigger class="w-full">
                <slot />
            </DialogTrigger>
            <DialogContent class="max-w-[600px]">
                <DialogHeader>
                    <DialogTitle>Добавить задачу</DialogTitle>
                    <DialogDescription>
                        Заполните форму, чтобы добавить новую задачу.
                    </DialogDescription>
                </DialogHeader>
                <div>
                    <form @submit.prevent="handleSubmit" action="" class="space-y-5 mt-5">
                        <div class="text-white text-lg space-y-2">
                            <label>
                                Название задачи
                            </label>
                            <Input v-model="form.title" type="text"
                                class="outline-none transition-all focus:!ring-2 dark:focus:!ring-white focus:border-transparent"></Input>
                        </div>
                        <div class="text-white text-lg space-y-2">
                            <label>
                                Описание задачи
                            </label>
                            <Textarea v-model="form.description" type="text"
                                class="outline-none transition-all focus:!ring-2 dark:focus:!ring-white focus:border-transparent"></Textarea>
                        </div>
                        <div class="text-white text-lg space-y-2">
                            <label>
                                Ответственный
                            </label>
                            <Select>
                                <SelectTrigger v-model="form.user_id"
                                    class="outline-none transition-all focus:!ring-2 dark:focus:!ring-white focus:border-transparent">
                                    <SelectValue placeholder="Выберите пользователя" />
                                </SelectTrigger>
                                <!-- <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Выберите ответственного пользователя</SelectLabel>
                                        <SelectItem v-for="(user, index) in props.projectUsers" :key="index"
                                            :value="user.user.id">
                                            <span v-if="(props.projectUsers as ProjectUser[])?.length > 0">{{ user.user.name }}</span>
                                            <span v-else>asdasdasd</span>
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent> -->
                                <SelectContent>
                                    <template v-if="hasProjectUsers">
                                        <SelectItem v-for="user in projectUsers" :key="user.user.id"
                                            :value="user.user.id">
                                            {{ user.user.name }}
                                        </SelectItem>
                                    </template>
                                    <template v-else>
                                        <SelectItem :value="currentUser.id" class="cursor-pointer">
                                            {{ currentUser.name }} (Вы)
                                        </SelectItem>
                                    </template>
                                </SelectContent>
                            </Select>
                            <!-- <select v-model="form.user_id" name="" id=""
                                class="bg-transparent px-2 py-2 w-full focus:border-white rounded-t-md border-x-0 border-t-0 focus:!border-x-0 focus:!border-t-0 focus:ring-0 border-b-white focus:bg-white/10">
                                <option selected :value="$page.props.auth.user.id" class="text-black">{{
                                    $page.props.auth.user.name
                                    }}</option>
                                <option :value="user.user.id"
                                    v-for="(user, index) in (props.projectUsers as ProjectUser[] | undefined)"
                                    :key="index" class="text-black">{{ user.user.name }}</option>
                            </select> -->
                        </div>
                        <div class="text-white text-lg space-y-2">
                            <label for="">Дедлайн</label>
                            <div class="flex items-center gap-5">
                                <Input v-model="form.start_date" type="text" v-date-mask
                                    class="outline-none transition-all focus:!ring-2 dark:focus:!ring-white focus:border-transparent"
                                    placeholder="Начало задачи"></Input>
                                <Input v-model="form.end_date" type="text" v-date-mask
                                    class="outline-none transition-all focus:!ring-2 dark:focus:!ring-white focus:border-transparent"
                                    placeholder="Конец задачи"></Input>
                                <!-- <input type="text" v-date-mask v-model="form.start_date" placeholder="Начало задачи"
                                    class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent"> -->
                                <!-- <input type="text" v-date-mask v-model="form.end_date" placeholder="Конец задачи"
                                    class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent"> -->
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-2 text-white bg-transparent border border-white rounded-md transition-all hover:bg-white/10">
                            Создать
                        </button>
                    </form>
                </div>
                <DialogFooter>
                    <!-- Save changes -->
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Main modal -->
        <!-- <div id="default-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full"> -->
        <!-- Modal content -->
        <!-- <div class="relative bg-white rounded-lg shadow-sm dark:bg-[#0d0d0d]"> -->
        <!-- Modal header -->
        <!-- <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Terms of Service
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div> -->
        <!-- Modal body -->
        <!-- <div class="px-10 pb-20">
                        <form @submit.prevent="handleSubmit" action="" class="space-y-5 mt-5">
                            <div class="text-white text-lg space-y-2">
                                <label>
                                    Название задачи
                                </label>
                                <input v-model="form.title" type="text"
                                    class="ring-0 outline-none focus:ring-0 focus:border-white focus:!border-x-0 bg-transparent w-full rounded-t-md border-x-0 border-t-0 focus:!border-t-0 border-b-white focus:bg-white/10 transition-all">
                            </div>
                            <div class="text-white text-lg space-y-2">
                                <label>
                                    Описание задачи
                                </label>
                                <textarea v-model="form.description" type="text"
                                    class="bg-transparent w-full focus:border-white min-h-24 rounded-t-md border-x-0 border-t-0 focus:!border-x-0 focus:!border-t-0 focus:ring-0 border-b-white focus:bg-white/10"></textarea>
                            </div>
                            <div class="text-white text-lg space-y-2">
                                <label>
                                    Ответственный
                                </label>
                                <select v-model="form.user_id" name="" id=""
                                    class="bg-transparent w-full focus:border-white rounded-t-md border-x-0 border-t-0 focus:!border-x-0 focus:!border-t-0 focus:ring-0 border-b-white focus:bg-white/10">
                                    <option selected class="text-black">Не назначено</option>
                                    <option :value="user.user.id"
                                        v-for="(user, index) in (props.projectUsers as ProjectUser[] | undefined)"
                                        :key="index" class="text-black">{{ user.user.name }}</option>
                                </select>
                            </div>
                            <div class="text-white text-lg space-y-2">
                                <label for="">Дедлайн</label>
                                <div class="flex items-center gap-5">
                                    <input type="text" v-date-mask v-model="form.start_date" placeholder="Начало задачи"
                                        class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                                    <input type="text" v-date-mask v-model="form.end_date" placeholder="Конец задачи"
                                        class="w-full !border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full py-2 text-white bg-transparent border border-white rounded-md transition-all hover:bg-white/10">
                                Создать
                            </button>
                        </form>
                    </div> -->
        <!-- Modal footer -->
        <!-- <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="default-modal" type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I
                            accept</button>
                        <button data-modal-hide="default-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                    </div> -->
        <!-- </div>
            </div>
        </div> -->


    </div>
</template>