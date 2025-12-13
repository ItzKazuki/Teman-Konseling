<template>
  <div class="space-y-8 max-w-lg mx-auto">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="space-y-3">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Ajukan Permintaan Konseling Baru</h1>
      <p class="text-gray-600 text-base">
        Ceritakan secara singkat masalah yang Anda hadapi. Informasi ini <span
          class="font-semibold text-red-600">bersifat rahasia</span> dan akan membantu kami menentukan Guru BK yang
        tepat.
      </p>
    </div>

    <form @submit.prevent="submitRequest" class="space-y-6">

      <div class="space-y-2">
        <label for="title" class="block text-sm font-medium text-gray-700">Judul Masalah</label>
        <input id="title" type="text" v-model="form.title" required placeholder="Contoh: Kesulitan Tidur dan Belajar"
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" />
      </div>

      <div class="space-y-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Masalah (Minimal 50
          Karakter)</label>
        <textarea id="description" v-model="form.description" rows="6" required
          placeholder="Contoh: Belakangan ini saya sering begadang karena cemas dengan nilai. Saya butuh bantuan untuk mengatur waktu belajar dan mengatasi kecemasan ini..."
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 resize-none text-base"></textarea>
        <p v-if="form.description.length < 50" class="text-xs text-red-500">
          Saat ini: {{ form.description.length }} karakter. Deskripsi harus minimal 50 karakter.
        </p>
      </div>

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">3. Tingkat Urgensi</h2>
        <p class="text-sm text-gray-600">Seberapa mendesak masalah ini perlu ditangani?</p>

        <div class="grid grid-cols-3 gap-3">
          <div v-for="level in urgencyLevels" :key="level.value" @click="form.urgency = level.value" :class="[
            'p-3 rounded-xl border-2 transition duration-200 cursor-pointer text-center',
            form.urgency === level.value
              ? 'border-primary-600 bg-primary-50 shadow-md'
              : 'border-gray-200 hover:border-gray-300'
          ]">
            <p :class="['font-semibold text-sm', urgencyColors[level.value]]">{{ level.label }}</p>
            <p class="text-xs text-gray-500">{{ level.desc }}</p>
          </div>
        </div>
      </div>

      <div class="pt-4">
        <button type="submit" :disabled="!isFormValid || isSubmitting" :class="[
          'w-full py-4 rounded-xl font-semibold transition-colors duration-200 text-center flex items-center justify-center',
          isFormValid && !isSubmitting
            ? 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400'
            : 'bg-gray-200 text-gray-500 cursor-not-allowed'
        ]">
          <span v-if="isSubmitting" class="flex items-center">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
            Mengirim Permintaan...
          </span>
          <span v-else>
            Kirim Permintaan & Lanjut Pilih Jadwal
          </span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed, ref } from 'vue';

const form = reactive({
  title: '',
  description: '',
  urgency: 'medium', // Default
});

const isSubmitting = ref(false);

const urgencyLevels = [
  { value: 'low', label: 'Rendah', desc: 'Bisa menunggu 1-2 hari' },
  { value: 'medium', label: 'Sedang', desc: 'Perlu ditangani segera' },
  { value: 'high', label: 'Tinggi', desc: 'Sangat mendesak/krisis' },
];

const urgencyColors = {
  low: 'text-green-600',
  medium: 'text-yellow-600',
  high: 'text-red-600',
};

// Computed untuk Validasi Form
const isFormValid = computed(() => {
  return (
    form.title.trim().length > 5 &&
    form.description.trim().length >= 50 &&
    form.urgency !== ''
  );
});

async function submitRequest() {
  if (!isFormValid.value || isSubmitting.value) return;

  isSubmitting.value = true;

  try {
    // 1. Kirim data ke API
    console.log('Permintaan baru diajukan:', form);
    await new Promise(resolve => setTimeout(resolve, 1500)); // Simulasi API

    // 2. Dapatkan ID Permintaan Baru (misalnya: newRequestId)
    const newRequestId = 'REQ-00123';

    // 3. Arahkan ke halaman pilihan konselor/jadwal
    // Kita mengirim ID permintaan yang baru dibuat
    alert("Permintaan berhasil dikirim! Sekarang pilih konselor Anda.");

    navigateTo({
      path: '/chats/counselors', // Halaman tempat memilih konselor
      query: { requestId: newRequestId }
    });

  } catch (error) {
    console.error("Gagal mengirim permintaan:", error);
    alert("Terjadi kesalahan. Mohon coba lagi.");
  } finally {
    isSubmitting.value = false;
  }
}
</script>