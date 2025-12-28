<template>
  <Transition name="fade">
    <div v-if="modal.show" class="fixed inset-0 z-50 center-flex bg-black/50 blur-bg">

      <div class="bg-white rounded-xl-lg shadow-soft w-full max-w-md p-6 relative mx-4 max-h-[80vh] overflow-y-auto">

        <button @click="modal.resolve(false)"
          class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-base cursor-pointer">
          <Icon name="mdi:close" class="w-5 h-5" />
        </button>

        <div class="flex items-center gap-3 mb-4">

          <template v-if="modal.type === 'success'">
            <Icon name="mdi:check-circle-outline" class="text-green-500 w-6 h-6" />
          </template>
          <template v-else-if="modal.type === 'error'">
            <Icon name="mdi:close-circle-outline" class="text-red-500 w-6 h-6" />
          </template>
          <template v-else-if="modal.type === 'warning'">
            <Icon name="mdi:alert-circle-outline" class="text-yellow-500 w-6 h-6" />
          </template>
          <template v-else>
            <Icon name="mdi:information-outline" class="text-primary-500 w-6 h-6" />
          </template>

          <span class="font-semibold capitalize text-lg text-slate-800">{{ modal.type }}</span>
        </div>

        <p class="text-sm text-slate-600 leading-relaxed mb-6">
          {{ modal.message }}
        </p>

        <div v-if="modal.actions.length" class="flex gap-3 justify-end pt-2">
          <button v-for="(action, index) in modal.actions" :key="index" @click="handleClick(action.label)" :class="[
            'px-4 py-2 rounded-lg font-semibold transition-base cursor-pointer',
            action.color
              ? action.color // Jika action.color diset (misalnya 'bg-red-500 text-white')
              : 'bg-primary-800 hover:bg-primary-900 text-white' // Default Confirm/Primary
          ]">
            {{ action.label }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
const modal = useAlert();

function handleClick(label: string) {
  if (label === 'Batal') modal.resolve(false);
  else modal.resolve(true);
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>