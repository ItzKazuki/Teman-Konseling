<template>
  <div v-if="toasts.length > 0" role="region" aria-live="polite"
    class="fixed top-4 right-4 z-9999 space-y-2 w-80 max-w-full pointer-events-none">

    <Toast v-for="toast in toasts" :key="toast.id" v-bind="toast" @close="remove(toast.id)" />
  </div>
</template>

<script setup lang="ts">
// Sekarang useToast() mengembalikan readonly(toasts)
const { toasts, remove } = useToast()
</script>