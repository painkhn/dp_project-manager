<script setup lang="ts">
import { defineProps, onMounted } from 'vue';
import { User, Team } from '@/types'
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps<{
    user: User;
    team: Team
    owner: User;
}>()

onMounted(() => {
    console.log(props.team);
    
})
</script>

<template>
    <Head :title="props.team.title" />

    <div class="max-w-4xl w-full p-5 bg-white/10 rounded-md mx-auto my-20">
        <h1 class="text-white text-center text-2xl font-bold mb-3">
            {{ props.team.title }}
        </h1>
        <p class="text-center text-white/90 w-10/12 mx-auto my-0 mb-3">
            {{ props.team.description }}
        </p>
        <div class="text-center">
            <Link :href="route('profile.index', { id: props.owner.id })" class="text-center text-white/90 inline">
                {{ props.owner.name }}
            </Link>
        </div>
        <h2 class="text-white text-lg font-semibold mb-5">
            Пользователи команды:
        </h2>
        <ul class="w-full flex flex-col">
            <li v-for="(user, index) in props.team.users" :key="index" class="w-full flex">
                <Link :href="route('profile.index', { id: props.owner.id })" class="text-lg rounded-md transition-all text-white px-4 py-2 bg-white/10 hover:bg-white/15 w-full">
                    {{ user.name }}
                </Link>
            </li>
        </ul>
    </div>
</template>