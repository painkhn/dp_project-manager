<!-- resources/js/Components/ErrorAlert.vue -->

<template>
    <div v-if="message" class="error-alert bg-white/5 p-5 rounded-md text-center">
      {{ message }}
    </div>
</template>
  
<script setup lang="ts">
    import { ref } from 'vue';
    
    const props = defineProps<{
        message: string;
    }>();
    
    const emit = defineEmits<{
        (e: 'close'): void;
    }>();
    
    const close = () => {
        emit('close');
    };
</script>