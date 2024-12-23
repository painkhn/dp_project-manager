<script setup lang="ts">
    import { defineProps, onMounted, defineEmits } from 'vue';
    import { ProjectUser, Project } from '@/types'
    import { Link } from '@inertiajs/vue3';
    import axios from 'axios';

    const props = defineProps<{
        projectUsers: ProjectUser[] | null;
        project: Project;
        isVisible: boolean;
    }>()

    const emit = defineEmits<{
        (e: 'toggle'): void;
    }>();

    const toggleProjectUsers = () => {
        emit('toggle');
    };

    const projectUserDelete = async (userId: number) => {
        try {
            await axios.delete(route('project.user.delete', { id: userId }));
            // После успешного удаления можно обновить список пользователей
            location.reload(); // Перезагрузка страницы
        } catch (error) {
            console.error('Ошибка при удалении пользователя:', error);
        }
    };

    onMounted(() => {
        // console.log(props.projectUsers);
    })
</script>

<template>
    <div>
        <ul class="mb-5 flex flex-col gap-2" v-if="(props.projectUsers as ProjectUser[])?.length > 0">
            <li v-for="(user, index) in props.projectUsers" :key="index">
                <div class="px-4 py-2 transition-all hover:bg-white/20 flex justify-between border-l-2 border-white">
                    <Link :href="route('profile.index', { id: user.user.id })" class="text-white text-lg hover:underline rounded-md transition-all">
                        {{ user.user.name }}
                    </Link>
                    <button @click="projectUserDelete(user.user.id)" class="transition-all hover:ring-1 hover:ring-white px-2 rounded-md" v-if="$page.props.auth.user.id == props.project.user.id">
                        Удалить из проекта
                    </button>
                </div>
            </li>
        </ul>
        <div v-else class="mb-5">
            <p class="text-white/85">
                Не добавлено ни одного пользователя
            </p>
        </div>
        <button @click="toggleProjectUsers()" class="w-full py-2 border border-white rounded-md transition-all hover:bg-white/10">
            Закрыть
        </button>
    </div>
</template>