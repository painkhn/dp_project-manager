<script setup lang="ts">
import { DefineProps } from 'vue';

const props = defineProps<{
    value: string;
}>()
    
</script>

<template>
    <div class="absolute bottom-5 right-5 max-w-xl">
        <div class="max-w-xl w-full h-auto p-5 text-right bg-white/10 rounded-md">
            <p class="text-white">
                {{ props.value }}
            </p>
        </div>
    </div>
</template>