<script setup lang="ts">
import { ref, defineProps, defineEmits, onMounted } from 'vue'
import axios from 'axios'
import Alert from '../messages/Alert.vue';

const props = defineProps<{
    projectId: number;
}>()

const emit = defineEmits(['fetch-invited-users'])

const searchQuery = ref('')
const users = ref<{ id: number; name: string }[]>([])
const selectedUser = ref<{ id: number; name: string } | null>(null)
const alertIsVisible = ref(false)
// const invitedUsers = ref<number[]>([])

const searchUsers = async () => {
    try {
        const response = await axios.get(`/api/users?name=${searchQuery.value}`)
        // users.value = response.data.filter((user: { id: number }) => !invitedUsers.value.includes(user.id) && user.id !== props.projectId)
        users.value = response.data
    } catch (error) {
        console.error(error)
    }
}

const selectUser = (user: { id: number; name: string }) => {
    selectedUser.value = user
    searchQuery.value = user.name
    users.value = []
}

const getAlert = () => {
    const alert = document.querySelector('.alert')
    alert?.classList.add('!opacity-100')
    setTimeout(function() {
        alert?.classList.remove('!opacity-100')
    }, 2000)
}

const sendInvitation = async () => {
    if (!selectedUser.value) return

    try {
        await axios.post(`/project/${props.projectId}/invite`, { invitee_id: selectedUser.value.id })
        location.reload()
        getAlert()
        selectedUser.value = null
        searchQuery.value = ''
        emit('fetch-invited-users', props.projectId)
    } catch (error) {
        console.error('Ошибка при отправке приглашения', error)
    }
}

// onMounted(async () => {
//     await fetchInvitedUsers()
// })
</script>

<template>
    <Alert class="alert opacity-0 transition-all" :value="'Успешно'" />
    <div class="flex flex-col gap-5 w-1/2">
        <div>
            <input v-model="searchQuery" type="text" placeholder="Имя пользователя" @input="searchUsers"
                class="w-full text-white border-0 bg-transparent border-b border-white focus:!border-0 focus:!border-b focus:border-white focus:ring-0 rounded-t-md focus:bg-white/5" />
            <ul v-if="users.length" class="bg-white/10 rounded-b-md flex flex-col gap-2">
                <!-- <select name="" id="" class="bg-white/10 border-0 text-white">
                    <option selected class="text-black">Выберите пользователя</option>
                    <option :value="user.id" v-for="user in users" :key="user.id" @click="selectUser(user)"
                        class="bg-white/10 text-black">
                        {{ user.name }}
                    </option>
                </select> -->
                <li v-for="user in users" :key="user.id" @click="selectUser(user)"
                    class="text-white rounded-b-md w-full px-4 py-2 hover:bg-white/10">
                    {{ user.name }}
                </li>
            </ul>
        </div>
        <button :disabled="!selectedUser" @click="sendInvitation"
            class="w-full border-2 py-2 border-white text-white disabled:opacity-50">Отправить приглашение</button>
    </div>
</template>
