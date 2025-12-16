<template>
  <div class="space-y-8">
    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo" class="h-6 w-auto" />
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
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 shadow-sm" />
      </div>

      <div class="space-y-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Masalah (Minimal 50
          Karakter)</label>
        <textarea id="description" v-model="form.description" rows="6" required
          placeholder="Contoh: Belakangan ini saya sering begadang karena cemas dengan nilai. Saya butuh bantuan untuk mengatur waktu belajar dan mengatasi kecemasan ini..."
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 resize-none text-base shadow-sm"></textarea>

        <div class="flex justify-between mt-1">
          <p v-if="form.description.length > 0 && form.description.length < 50"
            class="text-xs text-red-500 font-medium">
            Minimal 50 karakter (Kurang {{ 50 - form.description.length }} lagi)
          </p>
          <p class="text-xs text-gray-400 ml-auto">{{ form.description.length }} karakter</p>
        </div>
      </div>

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">Tingkat Urgensi</h2>
        <p class="text-sm text-gray-600">Seberapa mendesak masalah ini perlu ditangani?</p>

        <div class="grid grid-cols-3 gap-3">
          <div v-for="level in urgencyLevels" :key="level.value" @click="form.urgency = level.value" :class="[
            'p-3 rounded-xl border-2 transition duration-200 cursor-pointer text-center flex flex-col justify-center items-center h-full',
            form.urgency === level.value
              ? 'border-primary-600 bg-primary-50 shadow-md'
              : 'border-gray-200 hover:border-gray-300 bg-white'
          ]">
            <p :class="['font-bold text-sm mb-1', urgencyColors[level.value]]">{{ level.label }}</p>
            <p class="text-[10px] leading-tight text-gray-500">{{ level.desc }}</p>
          </div>
        </div>
      </div>

      <div class="pt-4">
        <button type="submit" :disabled="!isFormValid || isSubmitting" :class="[
          'w-full py-4 rounded-xl font-bold transition-all duration-200 text-center flex items-center justify-center gap-2',
          isFormValid && !isSubmitting
            ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-200'
            : 'bg-gray-200 text-gray-500 cursor-not-allowed'
        ]">
          <template v-if="isSubmitting">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
            Mengirim Permintaan...
          </template>
          <template v-else>
            <Icon name="heroicons:paper-airplane-solid" class="w-5 h-5" />
            Kirim Permintaan & Lanjut
          </template>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
const form = reactive({
  title: '',
  description: '',
  urgency: 'low',
});

const isSubmitting = ref(false);

const urgencyLevels = [
  { value: 'low', label: 'Rendah', desc: 'Bisa menunggu' },
  { value: 'medium', label: 'Sedang', desc: 'Perlu segera' },
  { value: 'high', label: 'Tinggi', desc: 'Sangat krisis' },
];

const urgencyColors = {
  low: 'text-green-600',
  medium: 'text-yellow-600',
  high: 'text-red-600',
};

// Validasi Form sesuai requirement API & UX
const isFormValid = computed(() => {
  return (
    form.title.trim().length >= 5 &&
    form.description.trim().length >= 50 &&
    form.urgency !== ''
  );
});

async function submitRequest() {
  if (!isFormValid.value || isSubmitting.value) return;

  isSubmitting.value = true;

  try {
    // Memanggil API sesuai CURL yang diberikan
    const res = await useApi().post('/student/counseling/new-request', {
      title: form.title,
      description: form.description,
      urgency: form.urgency
    });

    if (res.status) {
      useToast().success("Permintaan berhasil dikirim! Silakan pilih konselor.");

      navigateTo({
        path: '/chats/counselors',
        query: {
          requestId: res.data.request_id,
        }
      });
    } else {
      useToast().error(res.message || "Gagal mengirim permintaan.");
    }

  } catch (error) {
    console.error("API Error:", error);
    useToast().error("Terjadi kesalahan sistem. Mohon coba lagi nanti.");
  } finally {
    isSubmitting.value = false;
  }
}
</script>