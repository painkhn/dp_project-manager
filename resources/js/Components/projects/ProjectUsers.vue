<script setup lang="ts">
    import { defineProps, onMounted } from 'vue';
    import { ProjectUser, Project } from '@/types'

    const props = defineProps<{
        projectUsers: ProjectUser[] | null;
        project: Project;
    }>()

    onMounted(() => {
        // console.log(props.projectUsers);
    })
</script>

<template>
    <div>
        <ul>
            <li v-for="(user, index) in props.projectUsers" :key="index">
                <p>
                    {{ user.user_id }}
                </p>
            </li>
        </ul>
    </div>
</template>