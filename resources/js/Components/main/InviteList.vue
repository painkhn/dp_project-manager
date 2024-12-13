<script setup lang="ts">
import { DefineProps } from 'vue';
import { User } from '@/types'

const props = defineProps<{
    isVisible: boolean;
}>();

const emit = defineEmits<{
    (e: 'toggle'): void;
}>();

const invitesToggle = () => {
    emit('toggle');
};

// const props = defineProps<{
//     user: User
// }>()
</script>

<template>
    <div class="fixed top-0 left-0 w-full min-h-screen flex items-center justify-center bg-black/40 z-[41]">
        <div class="max-w-xl w-full h-auto bg-black border-white border rounded-md p-5">
            <!-- {{ $page.props.user.name }} -->
            <button @click="invitesToggle" class="text-white px-4 py-2 border border-white transition-all hover:bg-white/10 rounded-md ml-auto">Закрыть</button>
        </div>
    </div>
</template>