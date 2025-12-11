<template>
  <div class="space-y-8">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="space-y-2">
      <h1 class="text-2xl sm:text-3xl font-bold">Pilih Emosi Anda</h1>
      <p class="text-gray-500 text-base">Emosi apa yang sedang kamu rasakan saat ini?</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="(emotion, index) in emotions" :key="index" :class="[
        'aspect-square flex flex-col items-center justify-center rounded-xl p-4 cursor-pointer transition-all duration-200',
        'border border-gray-200 hover:border-primary-500 hover:shadow-md',
        { 'bg-primary-50 border-primary-600 shadow-lg': selectedEmotion === emotion.title }
      ]" @click="selectEmotion(emotion.title)">

        <Icon :name="`tk:emotion-${emotion.iconName}`" :class="{ 'scale-110': selectedEmotion === emotion.title }"
          class="w-1/2 h-1/2 object-contain mb-3 transition-transform duration-200" aria-hidden="true" />

        <p class="text-sm font-semibold text-center transition-all duration-200"
          :class="{ 'text-primary-600 font-extrabold': selectedEmotion === emotion.title }">
          {{ emotion.title }}
        </p>
      </div>
    </div>

    <Transition name="fade-slide">
      <div v-if="selectedEmotion" class="pt-6 space-y-6 bg-white p-4 rounded-xl shadow-lg border border-gray-100">

        <div class="space-y-3">
          <h2 class="text-lg font-bold text-gray-800">Seberapa besar rasa {{ selectedEmotion }} yang kamu rasakan?
          </h2>

          <div class="flex justify-between items-center gap-2">
            <div v-for="scale in 4" :key="scale" @click="emotionMagnitude = scale" :class="[
              'w-1/4 text-center cursor-pointer p-3 rounded-lg transition-all duration-200',
              emotionMagnitude === scale
                ? 'bg-secondary-600 text-white shadow-md scale-105 font-bold'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]">
              {{ scale }}
            </div>
          </div>
          <div class="flex justify-between text-xs text-gray-500 pt-1">
            <span>1 (Sangat Kecil)</span>
            <span class="text-right">4 (Sangat Besar)</span>
          </div>
        </div>

        <div class="space-y-2">
          <label for="user-story" class="text-lg font-bold text-gray-800">Ceritakan lebih lanjut (Opsional)</label>
          <textarea id="user-story" v-model="userStory"
            placeholder="Contoh: Saya merasa takut karena besok ada presentasi penting..." rows="4"
            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 resize-none text-base"></textarea>
          <p class="text-xs text-gray-500">Maks. 500 karakter.</p>
        </div>

      </div>
    </Transition>


    <div class="flex flex-col gap-3 pt-4">
      <button @click="proceedToConfirmation" :disabled="!isReadyToProceed" :class="[
        'w-full py-4 rounded-xl font-semibold transition-colors duration-200 text-center',
        isReadyToProceed
          ? 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400'
          : 'bg-gray-200 text-gray-500 cursor-not-allowed'
      ]">
        Kirim
      </button>

      <NuxtLink to="/mood-picker/custom" class="text-center text-sm font-medium text-gray-600 hover:text-black underline">
        Pilihan saya tidak ada di sini
      </NuxtLink>
    </div>
  </div>
</template>

<style scoped>
/* Transisi Sederhana untuk Bagian Baru */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>

<script setup lang="ts">
// State untuk menyimpan emosi yang dipilih
const selectedEmotion = ref<string | null>(null);
// State baru untuk besaran emosi (Skala 1-4)
const emotionMagnitude = ref<number | null>(null);
// State baru untuk cerita/deskripsi
const userStory = ref<string>('');

const emotions = [
  // ... data emosi Anda ...
  { title: 'Senang', iconName: 'happy' },
  { title: 'Sedih', iconName: 'sad' },
  { title: 'Marah', iconName: 'angry' },
  { title: 'Takut', iconName: 'nervous' },
  { title: 'Bangga', iconName: 'proud' },
  { title: 'Biasa Aja', iconName: 'biasa-aja' },
];

// Fungsi untuk memilih emosi
function selectEmotion(emotionTitle: string) {
  if (selectedEmotion.value === emotionTitle) {
    selectedEmotion.value = null;
    // Reset besaran dan cerita saat pilihan dibatalkan
    emotionMagnitude.value = null;
    userStory.value = '';
  } else {
    selectedEmotion.value = emotionTitle;
    // Set besaran default jika belum pernah dipilih
    if (emotionMagnitude.value === null) {
      emotionMagnitude.value = 2; // Default ke besaran menengah
    }
  }
}

// Fungsi untuk mengarahkan ke halaman konfirmasi dengan data (Opsional)
// Karena ini adalah tombol akhir, kita bisa menggunakan router untuk mengirim data state
function proceedToConfirmation() {
  if (selectedEmotion.value && emotionMagnitude.value) {
    // Contoh mengirim data melalui query params
    // Pastikan halaman konfirmasi Anda siap menerima ini
    // Atau gunakan state management (Pinia/Vuex) untuk data yang lebih kompleks
    navigateTo({
      path: '/mood-picker/konfirmasi',
      query: {
        emotion: selectedEmotion.value,
        magnitude: emotionMagnitude.value,
        story: userStory.value,
      }
    });
  }
}

// Computed property untuk mengecek apakah tombol sudah siap diklik
const isReadyToProceed = computed(() => {
  return selectedEmotion.value !== null && emotionMagnitude.value !== null;
});
</script>