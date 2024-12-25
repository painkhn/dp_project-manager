<script setup lang="ts">
    import { DefineProps } from 'vue';
    import { ref } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import { Project, User } from '@/types'

    const props = defineProps<{
        projects: Project[];
        user: User;
    }>()
</script>

<template>
    <div>
        <ul class="flex flex-col gap-5" v-if="(props.projects as Project[])?.length > 0">
            <li>
                <h2 class="font-bold text-white text-xl" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                    ВАШИ ПРОЕКТЫ
                </h2>
                <h2 class="font-bold text-white text-xl uppercase" v-else>
                    ПРОЕКТЫ ПОЛЬЗОВАТЕЛЯ {{ ($page.props.user as User).name }}
                </h2>
            </li>
            <li v-for="project in props.projects" :key="project.id">
                <Link :href="route('project.index', { id: project.id })">
                <div class="w-full h-auto px-4 py-2 transition-all hover:bg-white/10 border-l-4 border-white">
                    <h3 class="text-white font-bold text-2xl uppercase">
                        {{ project.title }}
                    </h3>
                    <p class="text-white/80">
                        {{ project.description }}
                    </p>
                </div>
                </Link>
            </li>
        </ul>
        <ul v-else>
            <h2 class="text-xl text-white text-center" v-if="$page.props.auth.user.id == ($page.props.user as User).id">
                Вы пока не создали ни одного проекта
            </h2>
            <h2 class="text-xl text-white text-center" v-else>
                Пользователь {{ ($page.props.user as User).name }} пока не создал ни одного проекта
            </h2>
        </ul>
    </div>
</template>