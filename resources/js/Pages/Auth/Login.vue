<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="Log in" />
    
    <div class="flex flex-col items-center justify-center w-full min-h-screen">
        <div class="max-w-2xl w-full mx-auto h-auto bg-white/10 p-5 rounded-md">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Электронная почта" />
        
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
        
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
        
                <div class="mt-4">
                    <InputLabel for="password" value="Пароль" />
        
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
        
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
        
                <div class="mt-4 flex items-center justify-end">
                    <Link
                        v-if="canResetPassword"
                        :href="route('register')"
                        class="rounded-md text-sm text-white underline hover:text-white/80 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Создать аккаунт
                    </Link>
        
                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Войти
                    </PrimaryButton>
                </div>
            </form>
        </div>
        <a :href="route('index')" class="text-white block mt-5">
            На главную
        </a>
    </div>

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
    </div>    
</template>
