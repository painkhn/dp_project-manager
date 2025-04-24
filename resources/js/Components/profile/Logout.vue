<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/Components/ui/dialog'
import Button from '../ui/button/Button.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const isOpen = ref(false);

const logout = () => {
    router.post(route('logout'));
};

const close = () => {
    isOpen.value = false
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Выход из аккаунта</DialogTitle>
                <DialogDescription>
                    Выберите действие, чтобы выйти из аккаунта или закрыть форму.
                </DialogDescription>
            </DialogHeader>
            <div class="space-x-4">
                <Button variant="destructive" type="submit" @click="logout">
                    Выйти из аккаунта
                </Button>
                <Button variant="outline" type="button" @click="close">
                    Отмена
                </Button>
            </div>
            <!-- <DialogFooter> -->
            <!-- Save changes -->
            <!-- </DialogFooter> -->
        </DialogContent>
    </Dialog>
</template>