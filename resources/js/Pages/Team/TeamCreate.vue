<script setup lang="ts">
import axios from 'axios';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/main/Header.vue';

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
            onError: (error) => {
                // errorMessage.value = Object.values(errors).join('\n');
                // console.log(errorMessage);
                console.log(error);                
            },
        });
    };
</script>

<template>
    <Header />

    <div class="max-w-xl w-full mx-auto my-0 p-5 rounded-md bg-white/10 text-white">
        <form @submit.prevent="handleSubmit" class="flex flex-col gap-5">
            <div class="flex flex-col gap-2">
                <label for="">Название проекта</label>
                <input type="text" v-model="form.title" class="!border-0 !border-b px-2 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="">Описание проекта</label>
                <textarea type="text" v-model="form.description" class="!border-0 !border-b px-2 py-2 h-40 focus:!ring-0 focus:!border-0 focus:!border-b focus:bg-white/5 rounded-t-md focus:!border-white ring-none border-white bg-transparent"></textarea>
            </div>
            <button type="submit" class="w-full py-2 border border-white rounded-md transition-all hover:bg-white/10">
                Создать
            </button>
        </form>
    </div>
</template>