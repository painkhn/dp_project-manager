<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import { initFlowbite } from 'flowbite';
    import Header from '@/Components/main/Header.vue';
    import Sidebar from '@/Components/main/Sidebar.vue';
    import ProjectInvite from '@/Components/projects/ProjectInvite.vue'

    const props = defineProps<{
        canLogin?: boolean;
        canRegister?: boolean;
        project: object;
        users: object;
    }>();

    const invitedUsers = ref<{ id: number; name: string }[]>([]);

    const fetchInvitedUsers = async (projectId: number) => {
        try {
            const response = await axios.get(`/project/${projectId}/invited_users`)
            invitedUsers.value = response.data
            console.log('Приглашенные пользователи:', response.data);
            // invitedUsers.value = response.data.map((user: { id: number }) => user.id)
        } catch (error) {
            console.error(error)
        }
    }

    onMounted(() => {
        initFlowbite();
        const projectId = props.project.id;
        fetchInvitedUsers(projectId);
    });

    const isSidebarVisible = ref(false);

    const sidebarToggle = () => {
        isSidebarVisible.value = !isSidebarVisible.value;
    };
</script>

<template>
    <Head :title="props.project.title" />

    <main>
        <div class="max-w-4xl mx-auto p-5 my-20 bg-white/10 rounded-xl">
            <h1 class="text-white font-bold text-2xl mb-5">
                {{ props.project.title }}
            </h1>
            <p class="text-white/90 font-semibold mb-5">
                {{ props.project.description }}
            </p>
            <p class="text-white/80 mb-5 flex items-center gap-3">
                {{ props.project.start_date }}
                <span>-</span>
                {{ props.project.end_date }}
            </p>
            <ProjectInvite :projectId="props.project.id" @fetch-invited-users="fetchInvitedUsers" />
            
            <div class="w-1/2">
                <h2 class="text-white text-xl mb-4 font-bold">
                    Приглашены:
                </h2>
                <ul class="flex flex-col gap-2 py-2 rounded-b-md bg-white/10" v-if="invitedUsers.length > 0">
                    <li v-for="user in invitedUsers" :key="user.id" class="">
                        <Link :href="route('profile.index', { id: user.id })" class="text-white font-semibold w-full h-full block px-4 py-2 hover:bg-white/10 rounded-b-md">
                            {{ user.name }}
                        </Link>
                    </li>
                    <!-- <li v-for="user in invitedUsers" :key="user.id" class="px-4 py-2">
                        {{ user.name }}
                    </li> -->
                </ul>
            </div>
        </div>
    </main>

    <button @click="sidebarToggle" class="absolute bottom-10 left-10 text-white font-bold z-10">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
        </svg>
    </button>

    <div v-if="isSidebarVisible" @click="sidebarToggle" class="fixed top-0 left-0 w-full h-full bg-black/40 z-[39]"></div>
    <Sidebar :isVisible="isSidebarVisible" @toggle="sidebarToggle" />
</template>