<script setup lang="ts">
    import { DefineProps, ref } from 'vue';
    import axios from 'axios';
    import { ProjectInvitation, Project } from '@/types'
    import Alert from '@/Components/messages/Alert.vue';
    import { Link } from '@inertiajs/vue3';


    const props = defineProps<{
        pendingInvitations: ProjectInvitation[] | null;
        project: Project;
    }>()

    const getAlert = () => {
        const alert = document.querySelector('.alert-deleted')
        alert?.classList.add('!opacity-100')
        setTimeout(function() {
            alert?.classList.remove('!opacity-100')
        }, 2000)
    }

    const deleteInvitation = async (invitationId: number) => {
        try {
            const response = await axios.delete(route('invitation.delete', { invitationId } ))
            // fetchInvitedUsers(invitationId)
            console.log('success');
            getAlert()
            location.reload()
        } catch (error) {
            console.log('Ошибка при удалении приглашения', error)
        }
    }
</script>

<template>
    <ul class="flex flex-col gap-2 py-2 rounded-b-md bg-white/10" v-if="props.pendingInvitations && props.pendingInvitations.length > 0">
        <li v-for="invitation in props.pendingInvitations" :key="invitation.id" class="flex items-center pr-4">
            <Link :href="route('profile.index', { id: invitation.invitee?.id })"
                class="text-white font-semibold w-full h-full block px-4 py-2 hover:bg-white/10 rounded-b-md">
                {{ invitation.invitee?.name }}
            </Link>
            <button @click="deleteInvitation(invitation.id)" v-if="$page.props.auth.user.id == props.project.user.id">
                <svg class="w-5 h-5 text-white transition-all hover:scale-125" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" 
                    />
                </svg>
            </button>
        </li>
    </ul>
    <p v-else class="text-white/80">Приглашений пока нет</p>
</template>