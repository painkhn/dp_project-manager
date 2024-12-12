<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps<{
    isVisible: boolean;
}>();

const emit = defineEmits<{
    (e: 'toggle'): void;
}>();

const avatarChangeIsVisible = () => {
    emit('toggle');
};

const avatarFile = ref(null);

const handleFileChange = (event: any) => {
    avatarFile.value = event.target.files[0];
};

const submitAvatarForm = async () => {
    if (!avatarFile.value) {
        alert('Пожалуйста, выберите файл для загрузки.');
        return;
    }

    const formData = new FormData();
    formData.append('avatar_change', avatarFile.value);

    try {
        await axios.post(route('new_avatar'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        // Обработка успешного изменения аватарки
        avatarFile.value = null; // Сбросить выбранный файл
        location.reload()
        // console.log('аватарка успешно изменена')
        // alert('Новая аватарка успешно установлена!')
    } catch (error) {
        // Обработка ошибок валидации
        console.error('Произошла ошибка при загрузке аватарки:', error);
        // alert('Произошла ошибка при загрузке аватарки: ' + error.response.data.errors.avatar_change.join(', '));
    }
}
</script>

<template>
    <div class="fixed w-full min-h-screen bg-black/40 left-0 top-0 flex items-center justify-center z-20">
        <div class="max-w-xl w-full top-1/2 left-[400px] z-10 bg-black border border-white rounded-md p-5">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                Изменить аватарку
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-white/90">
                Введите новую аватарку, которая будет отображаться в вашем профиле
            </p>
            <form id="avatar-file-form" @submit.prevent="submitAvatarForm" class="mt-5">
                <label class="flex items-center justify-center transition-all border-2 px-3 py-2 border-primary cursor-pointer w-1/2 mb-5 rounded-md hover:bg-white/10" for="avatar_change">
                    <input @change="handleFileChange" class="hidden" type="file" name="avatar_change" id="avatar_change" accept="image/*">
                    <span class="text-base text-gray-600 dark:text-white font-semibold">Сменить аватарку</span>
                </label>
                <button type="submit" class="bg-black transition-all text-white px-4 py-2 hover:bg-white/10 border border-white rounded-md uppercase text-xs font-semibold tracking-widest">Сохранить</button>
                <button type="button" @click="avatarChangeIsVisible" class="ml-5 transition-all hover:text-white/90">Отмена</button>
            </form>
        </div>
    </div>
</template>