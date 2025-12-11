<template>
  <div class="space-y-8">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="space-y-2">
      <h1 class="text-2xl sm:text-3xl font-bold">Buat Jadwal Konseling</h1>
      <p class="text-gray-600 text-base">Anda akan membuat jadwal dengan:</p>
      <div class="inline-flex items-center space-x-2 p-2 bg-primary-100 rounded-lg">
        <Icon name="tabler:user-circle" class="w-5 h-5 text-primary-600" />
        <p class="font-semibold text-primary-800">{{ counselorName || 'Memuat...' }}</p>
      </div>
    </div>

    <form @submit.prevent="submitSchedule" class="space-y-6">

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">1. Pilih Metode Konseling</h2>
        <div class="grid grid-cols-2 gap-4">
          
          <div @click="form.method = 'Chat'"
            :class="[
              'p-4 rounded-xl border-2 transition duration-200 cursor-pointer',
              form.method === 'Chat' 
                ? 'border-primary-600 bg-primary-50 shadow-md' 
                : 'border-gray-200 hover:border-gray-300'
            ]">
            <Icon name="tabler:message-circle-2" class="w-6 h-6 mb-1" 
              :class="form.method === 'Chat' ? 'text-primary-600' : 'text-gray-500'" />
            <p class="font-semibold text-gray-900">Chat (WhatsApp)</p>
            <p class="text-xs text-gray-500">Nyaman, cepat, dan fleksibel.</p>
          </div>

          <div @click="form.method = 'Tatap Muka'"
            :class="[
              'p-4 rounded-xl border-2 transition duration-200 cursor-pointer',
              form.method === 'Tatap Muka' 
                ? 'border-primary-600 bg-primary-50 shadow-md' 
                : 'border-gray-200 hover:border-gray-300'
            ]">
            <Icon name="tabler:users" class="w-6 h-6 mb-1" 
              :class="form.method === 'Tatap Muka' ? 'text-primary-600' : 'text-gray-500'" />
            <p class="font-semibold text-gray-900">Tatap Muka</p>
            <p class="text-xs text-gray-500">Konseling di ruang BK.</p>
          </div>
        </div>
      </div>

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">2. Tanggal dan Waktu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="schedule-date" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
            <input id="schedule-date" type="date" v-model="form.date" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" />
          </div>
          <div>
            <label for="schedule-time" class="block text-sm font-medium text-gray-700 mb-1">Pilih Jam</label>
            <select id="schedule-time" v-model="form.timeSlot" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
              <option value="" disabled>Pilih slot jam</option>
              <option v-for="slot in availableTimeSlots" :key="slot" :value="slot">{{ slot }}</option>
            </select>
          </div>
        </div>
        <p v-if="form.method === 'Tatap Muka'" class="text-xs text-secondary-600 pt-1">
          *Pastikan waktu ini tidak bentrok dengan jam pelajaran Anda.
        </p>
      </div>

      <div class="space-y-2">
        <h2 class="text-lg font-bold text-gray-800">3. Deskripsi Singkat Masalah (Rahasia)</h2>
        <textarea id="problem-desc" v-model="form.description" rows="4" required
          placeholder="Contoh: Saya kesulitan mengatur waktu belajar menjelang ujian..."
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 resize-none text-base"></textarea>
        <p class="text-xs text-gray-500">Deskripsi ini bersifat rahasia dan hanya dilihat oleh Guru BK.</p>
      </div>

      <div class="pt-4">
        <button type="submit" :disabled="!isFormValid"
          :class="[
            'w-full py-4 rounded-xl font-semibold transition-colors duration-200 text-center',
            isFormValid
              ? 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400'
              : 'bg-gray-200 text-gray-500 cursor-not-allowed'
          ]">
          Ajukan Jadwal Konseling
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed, ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

// State Form
const form = reactive({
  counselorId: null,
  method: 'Chat', // Default ke Chat
  date: '',
  timeSlot: '',
  description: '',
});

// Data Konselor dari Query Params
const counselorName = ref('Guru BK');

// Slot Waktu Tersedia (Dummy)
const availableTimeSlots = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00'];

onMounted(() => {
  // Ambil data konselor dari URL Query Params
  const id = route.query.counselorId;
  const name = route.query.counselorName;

  if (id && name) {
    form.counselorId = id;
    counselorName.value = name;
  } else {
    // Jika tidak ada data, arahkan kembali atau tampilkan pesan error
    // navigateTo('/counselors');
    console.error("Data konselor tidak ditemukan!");
  }
  
  // Set tanggal default ke hari ini
  form.date = new Date().toISOString().slice(0, 10);
});

// Computed untuk Validasi Form
const isFormValid = computed(() => {
  return (
    form.counselorId !== null &&
    form.method !== '' &&
    form.date !== '' &&
    form.timeSlot !== '' &&
    form.description.trim().length >= 10 // Minimal deskripsi 10 karakter
  );
});

// Fungsi Submit
function submitSchedule() {
  if (!isFormValid.value) return;

  // Logika Pengiriman Data (Simulasi)
  console.log('Jadwal diajukan:', form);
  
  // Di sini Anda akan mengirim data ke API backend
  
  // Setelah berhasil, arahkan ke halaman konfirmasi atau riwayat jadwal
  alert(`Permintaan jadwal dengan ${counselorName.value} berhasil diajukan pada ${form.date} jam ${form.timeSlot} via ${form.method}. Menunggu konfirmasi Guru BK.`);
  navigateTo('/schedule/history'); 
}
</script>