<template>
  <div v-if="show" class="fixed inset-0 z-40 bg-black/50 bg-opacity-50 transition-opacity duration-300"
    @click="closeModal"></div>

  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" aria-modal="true"
      role="dialog">

      <div class="relative bg-white rounded-xl shadow-2xl max-h-[90vh] overflow-hidden" :class="maxWidthClass"
        @click.stop>

        <div class="p-5 border-b flex justify-between items-center">
          <h4 class="text-xl font-semibold text-gray-800">{{ title }}</h4>
          <button @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100">
            <Icon name="tabler:x" class="w-6 h-6" />
          </button>
        </div>

        <div class="p-5 overflow-y-auto max-h-[calc(90vh-70px)]">
          <slot />
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
const props = defineProps<{
  show: boolean;
  title: string;
  /** Ukuran lebar maksimum modal (sm, md, lg, xl, 2xl, etc.) */
  maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl';
}>();

const emit = defineEmits<{
  (e: 'close'): void;
}>();

// Menghitung kelas CSS untuk lebar modal
const maxWidthClass = computed(() => {
  switch (props.maxWidth) {
    case 'sm': return 'max-w-sm';
    case 'md': return 'max-w-md';
    case 'lg': return 'max-w-lg';
    case 'xl': return 'max-w-xl';
    case '2xl': return 'max-w-2xl';
    case '3xl': return 'max-w-3xl';
    case '4xl': return 'max-w-4xl';
    default: return 'max-w-lg';
  }
});

// Fungsi untuk menutup modal
const closeModal = () => {
  emit('close');
};
</script>

<style>
/* CSS untuk Transisi Modal (Contoh) */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>