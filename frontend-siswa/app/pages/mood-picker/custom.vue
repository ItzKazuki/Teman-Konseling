<template>
  <div class="space-y-8">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="space-y-2">
      <h1 class="text-2xl sm:text-3xl font-bold">Deskripsikan Emosi Anda</h1>
      <p class="text-gray-500 text-base">Tidak masalah! Emosi bisa kompleks. Ceritakan apa yang kamu rasakan saat ini.
      </p>
    </div>

    <div class="space-y-6 bg-white p-4 rounded-xl shadow-lg border border-gray-100">

      <div class="space-y-2">
        <label for="custom-emotion-name" class="text-lg font-bold text-gray-800">Nama Emosi</label>
        <p class="text-xs">Contoh: Bingung, Iri, Lega, dll</p>
        <input id="custom-emotion-name" v-model="customEmotionName" type="text"
          placeholder="Tulis emosi spesifik Anda di sini..."
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-base"
          required />
      </div>

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">Seberapa besar emosi ini kamu rasakan?</h2>

        <div class="flex justify-between items-center">
          <div v-for="scale in 4" :key="scale" @click="emotionMagnitude = scale" :class="[
            'w-1/4 text-center cursor-pointer p-3 rounded-lg transition-all duration-200 mx-1',
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
        <label for="user-story-custom" class="text-lg font-bold text-gray-800">Ceritakan lebih lanjut</label>
        <textarea id="user-story-custom" v-model="userStory"
          placeholder="Ceritakan kejadian, pikiran, atau perasaan yang memicu emosi ini..." rows="5"
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 resize-none text-base"
          required></textarea>
        <p class="text-xs text-gray-500">Minimal 10 karakter.</p>
      </div>

    </div>

    <div class="flex flex-col gap-3 pt-4">
      <button @click="proceedToConfirmation" :disabled="!isReadyToProceed || isSubmitting" :class="[
        'w-full py-4 rounded-xl font-semibold transition-colors duration-200 text-center',
        isReadyToProceed
          ? 'bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-2 focus:ring-secondary-400'
          : 'bg-gray-200 text-gray-500 cursor-not-allowed'
      ]">
        <span v-if="isSubmitting" class="flex items-center gap-2">
          <div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
          Mengirim...
        </span>
        <span v-else>Kirim</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'custom'
});

const isSubmitting = ref(false);

const customEmotionName = ref('');
const emotionMagnitude = ref<number | null>(null);
const userStory = ref('');

// Computed property untuk validasi (Wajib mengisi nama emosi, besaran, dan cerita)
const isReadyToProceed = computed(() => {
  return (
    customEmotionName.value.trim().length > 0 &&
    emotionMagnitude.value !== null &&
    userStory.value.trim().length >= 10 // Pastikan cerita minimal 10 karakter
  );
});

async function proceedToConfirmation() {
  isSubmitting.value = true;

  if (isReadyToProceed.value) {
    try {
      const res = await useApi().post('/student/daily-moods', {
        emotion_name: customEmotionName.value,
        magnitude: emotionMagnitude.value,
        story: userStory.value,
        is_custom: true
      });

      if (res.status) {
        navigateTo('/mood-picker/konfirmasi');
      }
    } catch (error: any) {
      alert(error.data?.message || "Gagal menyimpan emosi kustom");
    } finally {
      isSubmitting.value = false;
    }
  }
}

onMounted(async () => {
  try {
    const res = await useApi().get<{ has_filled_today: boolean }>('/student/daily-moods/check');
    if (res.status && res.data.has_filled_today) {
      useToast().info("Kamu sudah mengisi emosi hari ini!");
      navigateTo('/home');
    }
  } catch (e) {
    console.error("Gagal cek status mood");
  }
});
</script>