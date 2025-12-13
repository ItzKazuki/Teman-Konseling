<template>
  <div class="space-y-8">

    <AppHeader title="Pilih Konselor Anda" icon="tk:chat-bold" />

    <p class="text-gray-600">Pilih Guru BK yang tersedia di bawah ini untuk membuat jadwal sesi konseling.</p>

    <div class="space-y-4">

      <div v-if="isLoading" class="text-center py-10">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div>
        <p class="mt-4 text-gray-500">Memuat daftar konselor...</p>
      </div>

      <div v-else-if="availableCounselors.length > 0">
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
                  {{ counselor.isAvailable ? 'Menerima Jadwal' : 'Penuh Hari Ini' }}
                </p>
              </div>

              <div class="mt-3">
                <button @click="scheduleConsultation(counselor)" :disabled="!counselor.isAvailable" :class="[
                  'py-2 px-4 rounded-lg font-semibold text-sm transition duration-150',
                  counselor.isAvailable
                    ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm'
                    : 'bg-gray-200 text-gray-500 cursor-not-allowed'
                ]">
                  Buat Jadwal
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12 px-6">
        <p class="text-lg font-semibold text-gray-700">Kamu belum mengajukan konseling.</p>
        <p class="mt-2 text-gray-500">Ajukan konseling sekarang untuk melihat daftar konselor yang tersedia.</p>

        <div class="mt-6">
          <NuxtLink to="/chats/new-request"
            class="inline-block py-3 px-6 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition duration-150">
            Ajukan Konseling Sekarang
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { navigateTo } from '#app'; // Pastikan `MapsTo` diimport jika menggunakan Nuxt 3

const availableCounselors = ref([]); // Data awal dikosongkan
const isLoading = ref(true); // Status loading

// Simulasikan pengambilan data dari API
const fetchData = () => {
  isLoading.value = true;
  setTimeout(() => {
    // Kosongkan data secara default (untuk menampilkan placeholder)
    // availableCounselors.value = []; 

    // Jika ingin menampilkan data setelah loading (untuk testing):
    // availableCounselors.value = [
    //   {
    //     id: 1,
    //     name: "Bpk. Dwi Prasetyo, M.Pd",
    //     title: "Guru BK Kelas X & XI",
    //     phoneNumber: "6281234567890",
    //     photoUrl: "/static/images/profile.png",
    //     isAvailable: true,
    //   },
    //   {
    //     id: 2,
    //     name: "Ibu Rina Amalia, S.Psi",
    //     title: "Guru BK Kelas XII",
    //     phoneNumber: "6285098765432",
    //     photoUrl: "/static/images/profile.png",
    //     isAvailable: false,
    //   },
    //   {
    //     id: 3,
    //     name: "Bpk. Budi Santoso, S.Pd",
    //     title: "Guru BK Umum",
    //     phoneNumber: "6287112233445",
    //     photoUrl: "/static/images/profile.png",
    //     isAvailable: true,
    //   },
    // ];

    isLoading.value = false;
  }, 1000); // Simulasi delay 1 detik
};

onMounted(() => {
  fetchData();
});

function scheduleConsultation(counselor) {
  if (!counselor.isAvailable) {
    alert(`Maaf, ${counselor.name} sedang penuh jadwal. Silakan pilih konselor lain atau coba besok.`);
    return;
  }

  navigateTo({
    path: '/chats/schedule/form',
    query: {
      counselorId: counselor.id,
      counselorName: counselor.name,
    }
  });

  console.log(`Mengarahkan ke halaman jadwal untuk ${counselor.name}.`);
}
</script>