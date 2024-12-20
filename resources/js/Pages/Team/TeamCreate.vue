<script setup lang="ts">
import axios from 'axios';
import { Head, Link, useForm } from '@inertiajs/vue3';

// const createTeam = async (userId: number) => {
//     const response = await axios.post(route('team.store'), {
//         user_id: userId,
//         title: title.value,
//         description: description.value,
//     })
// }

const form = useForm({
    title: '',
    description: '',
});

const handleSubmit = () => {
        form.post(route('team.store'), {
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
    <div class="max-w-xl w-full mx-auto my-10 p-5 rounded-md bg-white/10">
        <form @submit.prevent="handleSubmit">
            <div class="flex flex-col gap-2">
                <label for="">Название проекта</label>
                <input type="text" v-model="form.title" class="!border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="">Описание проекта</label>
                <textarea type="text" v-model="form.description" class="!border-0 !border-b px-2 py-2 h-40 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent"></textarea>
            </div>
            <button type="submit">
                Создать
            </button>
        </form>
    </div>
</template>