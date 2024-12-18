<script setup lang="ts">
    import { defineProps, onMounted, defineEmits } from 'vue';
    import { ProjectUser, Project } from '@/types'
    import { Link } from '@inertiajs/vue3';

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

    onMounted(() => {
        // console.log(props.projectUsers);
    })
</script>

<template>
    <div>
        <ul class="mb-5 flex flex-col gap-2">
            <li v-for="(user, index) in props.projectUsers" :key="index">
                <Link :href="route('profile.index', { id: user.user.id })" class="text-white text-lg">
                    <div class="px-4 py-2 bg-white/10 rounded-md transition-all hover:bg-white/20">
                        {{ user.user.name }}
                    </div>
                </Link>
            </li>
        </ul>
        <button @click="toggleProjectUsers()" class="w-full py-2 border border-white rounded-md">
            Закрыть
        </button>
    </div>
</template>