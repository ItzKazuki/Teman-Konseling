<template>
  <transition name="fade">
    <div v-if="!isHidden" role="alert"
      class="flex items-start rounded-lg p-4 shadow-xl w-80 max-w-sm pointer-events-auto"
      :class="variantClasses.container">

      <Icon :name="variantClasses.icon" size="20" class="mt-0.5" />

      <div class="ml-3 text-sm font-medium flex-1 wrap-break-words leading-snug">
        {{ text }}
      </div>

      <button v-if="closable" @click="closeAlert" aria-label="Tutup notifikasi"
        class="ml-3 inline-flex h-6 w-6 items-center justify-center rounded-md shrink-0 cursor-pointer -mt-1"
        :class="variantClasses.closeButtonHover">
        <Icon name="mdi:close" size="18" />
      </button>
    </div>
  </transition>
</template>

<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    type?: ToastType;
    text: string;
    closable?: boolean;
    duration?: number; // Durasi dalam milidetik untuk menutup otomatis. 0 = non-aktif.
  }>(),
  { type: 'info', closable: true, duration: 5000 } // Default: 5 detik
);

const isHidden = ref<boolean>(false);
let timeout: ReturnType<typeof setTimeout> | null = null;

function closeAlert() {
  isHidden.value = true;
  if (timeout) {
    clearTimeout(timeout);
  }
}

// Objek tunggal untuk mengelola semua kelas terkait varian
const allVariantClasses: Record<VariantKey, { container: string, closeButtonHover: string, icon: string }> = {
  default: {
    container: 'bg-white text-gray-800 border border-gray-200',
    closeButtonHover: 'hover:bg-gray-100',
    icon: 'mdi:information-outline',
  },
  info: {
    container: 'bg-primary-100 text-primary-800',
    closeButtonHover: 'hover:bg-primary-200',
    icon: 'mdi:information',
  },
  success: {
    container: 'bg-green-100 text-green-800',
    closeButtonHover: 'hover:bg-green-200',
    icon: 'mdi:check-circle', // Mengubah icon untuk kesan lebih formal/lengkap
  },
  warning: {
    container: 'bg-yellow-100 text-yellow-800',
    closeButtonHover: 'hover:bg-yellow-200',
    icon: 'mdi:alert-box',
  },
  error: {
    container: 'bg-red-100 text-red-800',
    closeButtonHover: 'hover:bg-red-200',
    icon: 'mdi:close-octagon',
  },
};

const variantClasses = computed(() => {
  const key = (props.type ?? 'default') as VariantKey;
  return allVariantClasses[key];
});

onMounted(() => {
  if (props.duration > 0) {
    timeout = setTimeout(closeAlert, props.duration);
  }
});

onUnmounted(() => {
  if (timeout) {
    clearTimeout(timeout);
  }
});

defineExpose({
  closeAlert
});
</script>

<style>
/* Menambahkan transisi untuk posisi (misalnya, jika Anda menggunakan transform: translateY) */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
  /* Efek slide ringan saat masuk/keluar */
}
</style>
