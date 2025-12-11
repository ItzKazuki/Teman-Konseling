<template>
  <div class="space-y-8">
    <AppHeader />

    <p class="text-gray-600">Pilih Guru BK yang tersedia di bawah ini untuk memulai sesi konseling.</p>

    <div class="space-y-4">
      <div v-for="counselor in availableCounselors" :key="counselor.id"
        class="bg-white p-4 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-200">

        <div class="flex items-start space-x-4">
          <img :src="counselor.photoUrl" :alt="`Foto ${counselor.name}`"
            class="w-16 h-16 object-cover rounded-full border-2 border-primary-200 shrink-0" />

          <div class="grow">
            <h3 class="text-lg font-bold text-gray-900">{{ counselor.name }}</h3>
            <p class="text-sm text-gray-600">{{ counselor.title }}</p>

            <div class="flex items-center mt-1">
              <span
                :class="['w-2.5 h-2.5 rounded-full mr-2', counselor.isAvailable ? 'bg-green-500' : 'bg-red-500']"></span>
              <p :class="['text-xs font-semibold', counselor.isAvailable ? 'text-green-600' : 'text-red-600']">
                {{ counselor.isAvailable ? 'Tersedia Sekarang' : 'Sedang Sibuk' }}
              </p>
            </div>
          </div>

          <!-- <div class="shrink-0 self-center">
            <button
              @click="startChat(counselor)"
              :disabled="!counselor.isAvailable"
              :class="[
                'py-2 px-4 rounded-lg font-semibold text-sm transition duration-150',
                counselor.isAvailable 
                  ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm' 
                  : 'bg-gray-200 text-gray-500 cursor-not-allowed'
              ]"
            >
              Mulai Chat
            </button>
          </div> -->
        </div>
      </div>

      <div v-if="!availableCounselors.length" class="text-center py-10 text-gray-500">
        <p>Saat ini tidak ada Guru BK yang tersedia untuk konseling.</p>
        <p class="mt-2">Silakan coba beberapa saat lagi.</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

// 1. Data Dummy Guru BK
const availableCounselors = reactive([
  {
    id: 1,
    name: "Bpk. Dwi Prasetyo, M.Pd",
    title: "Guru BK Kelas X & XI",
    phoneNumber: "6281234567890",
    photoUrl: "/static/images/profile.png", // Ganti dengan path foto
    isAvailable: true,
  },
  {
    id: 2,
    name: "Ibu Rina Amalia, S.Psi",
    title: "Guru BK Kelas XII",
    phoneNumber: "6285098765432",
    photoUrl: "/static/images/profile.png", // Ganti dengan path foto
    isAvailable: false, // Sedang sibuk
  },
  {
    id: 3,
    name: "Bpk. Budi Santoso, S.Pd",
    title: "Guru BK Umum",
    phoneNumber: "6287112233445",
    photoUrl: "/static/images/profile.png", // Ganti dengan path foto
    isAvailable: true,
  },
]);

// 2. Fungsi untuk Memulai Chat (WhatsApp Redirect)
function startChat(counselor) {
  if (!counselor.isAvailable) {
    alert(`Maaf, ${counselor.name} sedang sibuk. Silakan pilih konselor lain.`);
    return;
  }

  // Asumsi data siswa sudah ada di state (misalnya, nama dan kelas)
  const studentName = "Nama Siswa"; // Ganti dengan data siswa yang sebenarnya
  const studentInfo = "Kelas XI IPA 2"; // Ganti dengan data siswa yang sebenarnya

  const initialMessage = `Halo Bapak/Ibu ${counselor.name}, saya ${studentName} (${studentInfo}). Saya ingin memulai sesi konseling. Apakah Bapak/Ibu bersedia menerima sesi saya sekarang?`;

  const encodedMessage = encodeURIComponent(initialMessage);
  const waUrl = `https://wa.me/${counselor.phoneNumber}?text=${encodedMessage}`;

  // Membuka tab baru atau aplikasi WhatsApp
  window.open(waUrl, '_blank');

  console.log(`Mengarahkan ${studentName} ke chat dengan ${counselor.name}.`);
}
</script>