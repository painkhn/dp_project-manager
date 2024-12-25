<script setup lang="ts">
    import { DefineProps } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import { Teams, User } from '@/types'

    const props = defineProps<{
        teams: Teams[] | null;
        user: User;
    }>()

</script>

<template>
    <div>
        <h2 class="text-white text-xl font-bold mb-5 uppercase" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
            Ваша команда
        </h2>
        <h2 v-else class="text-white text-xl font-bold mb-5 uppercase">
            Команда пользователя {{ props.user.name }}
        </h2>
        <ul v-if="(props.teams as Teams[])?.length > 0 && $page.props.auth.user.id == ($page.props.user as User).id">
            <ul>
                <li v-for="(team, index) in props.teams" :key="team.id" class="text-white">
                    <Link :href="route('team.page', { id: team.id })">
                        <div class="w-full py-2 bg-white/10 px-4 rounded-md transition-all hover:bg-white/15">
                            <h2 class="text-white font-semibold text-xl">
                                {{ team.title }}
                            </h2>
                        </div>
                    </Link>
                </li>
            </ul>
        </ul>
        <p v-else-if="$page.props.auth.user.id == ($page.props.user as User).id" class="text-white/80 text-xl">
            Вы не состоите ни в одной команде
        </p>
        <p v-else class="text-white/80 text-xl">
            Пользователь {{ props.user.name }} не состоит в команде
        </p>
        <Link :href="route('team.create')" class="text-white mt-5 block" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
            <button type="button" class="w-full py-2 bg-transparent transition-all border border-white rounded-md hover:bg-white/10">
                Создать команду
            </button>
        </Link>
    </div>
</template>

<style>

</style>