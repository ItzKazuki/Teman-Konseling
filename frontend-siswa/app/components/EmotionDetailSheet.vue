<template>
  <Transition name="slide-fade">
    <div v-if="show" class="fixed inset-0 z-50 sm:z-60">

      <div class="absolute inset-0 bg-black/50" @click="$emit('close')"></div>

      <div
        class="absolute bottom-0 w-full max-h-[80vh] bg-white rounded-t-2xl shadow-2xl p-6 overflow-y-auto sm:left-1/2 sm:transform sm:-translate-x-1/2 sm:max-w-md">
        <div class="flex justify-center mb-4">
          <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
        </div>

        <div v-if="emotion" class="text-center space-y-4 pt-4">

          <Icon :name="`tk:emotion-${emotion.iconName}`" class="w-20 h-20 mx-auto text-primary-600" />

          <h2 class="text-2xl font-extrabold text-gray-900">{{ emotion.title }}</h2>

          <p class="text-gray-700 text-base leading-relaxed text-left">
            {{ emotion.description }}
          </p>

          <div class="pt-4 space-y-3">
            <NuxtLink to="/mood-picker"
              class="w-full py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 block">
              Lanjut ke Mood Tracking
            </NuxtLink>
            <NuxtLink to="/chat"
              class="w-full py-3 border border-primary-600 text-primary-600 rounded-lg font-semibold hover:bg-primary-50 block">
              Ingin Konseling?
            </NuxtLink>
          </div>

        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  emotion: {
    type: Object,
    default: null,
  },
});

defineEmits(['close']);
</script>

<style scoped>
/* Transisi Overlay (Hitam transparan) */
.slide-fade-enter-active> :first-child,
.slide-fade-leave-active> :first-child {
  transition: opacity 0.3s ease;
}

/* Transisi Konten Sheet (Slide dari bawah) */
.slide-fade-enter-active> :last-child,
.slide-fade-leave-active> :last-child {
  /* Pastikan transisi hanya pada transform untuk kinerja terbaik */
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  /* Gunakan cubic-bezier untuk efek bounce/ease yang lebih baik */
}

/* Keadaan Awal/Akhir (Ketika Sheet di luar layar) */
.slide-fade-enter-from> :last-child,
.slide-fade-leave-to> :last-child {
  /* Memindahkan konten sheet ke luar layar (bawah) */
  transform: translateY(100%);
}

/* Keadaan Awal/Akhir (Opacity Overlay) */
.slide-fade-enter-from> :first-child,
.slide-fade-leave-to> :first-child {
  opacity: 0;
}
</style>