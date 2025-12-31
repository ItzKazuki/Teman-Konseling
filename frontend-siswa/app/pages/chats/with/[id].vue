<template>
  <div class="space-y-8">
    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo" class="h-6 w-auto" />
    </div>

    <div v-if="isLoading" class="flex flex-col items-center justify-center py-20 animate-fade-in">
      <div class="relative flex items-center justify-center">
        <div class="absolute inset-0 bg-primary-200 rounded-full blur-xl opacity-40 animate-pulse"></div>

        <Icon name="svg-spinners:ring-resize" class="w-12 h-12 text-primary-600 relative z-10" />
      </div>

      <div class="mt-4 text-center">
        <p class="text-lg font-bold text-gray-800">Mohon Tunggu</p>
        <p class="text-sm text-gray-500">Sedang memuat info sesi konseling kamu...</p>
      </div>
    </div>

    <template v-else-if="counselingDetail">
      <div class="flex items-center space-x-4 pb-2 border-b border-gray-100">
        <Icon name="tk:chat-bold" class="w-7 h-7 text-primary-600" />
        <h1 class="text-2xl font-bold text-gray-900">Mulai Konseling Chat</h1>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 space-y-4">
        <h2 class="text-xl font-extrabold text-gray-800 flex items-center gap-2">
          <Icon name="tk:profile-bold" class="w-5 h-5" />
          Konselor BK Anda
        </h2>

        <div class="flex items-center space-x-4">
          <img :src="counselor.photoUrl" :alt="counselor.name"
            class="w-16 h-16 object-cover rounded-full border-2 border-primary-100 shadow-sm" />

          <div>
            <p class="text-lg font-semibold text-gray-900">{{ counselor.name }}</p>
            <p class="text-sm text-gray-600">{{ counselor.jabatan }}</p>
            <div class="flex items-center mt-1">
              <span
                :class="['w-2 h-2 rounded-full mr-2', counselingDetail.schedule?.counselor?.is_available ? 'bg-green-500' : 'bg-gray-400']"></span>
              <p class="text-sm font-medium text-primary-600">{{ counselor.availability }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-primary-50 p-4 rounded-xl border border-primary-100">
        <p class="text-sm text-primary-800 font-bold mb-1">Topik Konseling:</p>
        <p class="text-gray-700 italic">"{{ counselingDetail.title }}"</p>
      </div>

      <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-800">Langkah Sebelum Chat</h2>
        <ol class="list-decimal list-inside text-gray-700 space-y-3 pl-4">
          <li>Pastikan Anda sudah siap berbagi mengenai masalah Anda.</li>
          <li>Waktu respon konselor bervariasi tergantung kesibukan.</li>
          <li>Chat menggunakan WhatsApp. Pastikan aplikasi sudah terinstal.</li>
          <li>Sesi bersifat rahasia (Kode Etik BK).</li>
        </ol>
      </div>

      <div class="fixed inset-x-0 bottom-0 p-4 z-100 bg-white border-t border-gray-200 
                  sm:static sm:p-0 sm:border-none sm:max-w-md sm:mx-auto">
        <button @click="openWhatsAppChat" class="w-full py-4 bg-green-500 text-white rounded-xl font-bold text-lg 
                 hover:bg-green-600 transition duration-200 shadow-lg shadow-green-500/40 
                 flex items-center justify-center gap-3">
          <Icon name="logos:whatsapp-icon" class="w-6 h-6" />
          Mulai Chat Sekarang
        </button>
      </div>
    </template>

    <div v-else class="text-center py-20 text-red-500 font-medium">
      Gagal memuat data konseling. Silakan kembali.
    </div>
  </div>
</template>

<script setup>
const route = useRoute();
const id = route.params.id; // Mengambil ID dari nama file [id].vue

const isLoading = ref(true);
const counselingDetail = ref(null);

// Data default untuk placeholder sebelum API selesai dimuat
const counselor = computed(() => {
  const data = counselingDetail.value?.schedule?.counselor;
  return {
    name: data?.name || "Memuat...",
    phoneNumber: data?.phone_number || "",
    photoUrl: data?.avatar_url || "/static/images/profile.png",
    availability: data?.is_available ? "Tersedia untuk Chat" : "Sedang Tidak Aktif",
    jabatan: data?.jabatan || "Guru BK"
  };
});

const fetchData = async () => {
  isLoading.value = true;
  try {
    const res = await useApi().get(`/student/counseling/${id}`);
    if (res.status) {
      counselingDetail.value = res.data;
    }
  } catch (error) {
    console.error('Error fetching detail:', error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchData();
});

function openWhatsAppChat() {
  if (!counselor.value.phoneNumber) {
    useToast().error("Nomor WhatsApp konselor tidak tersedia.");
    return;
  }

  const message = `Halo ${counselor.value.name}, saya ingin memulai sesi konseling terkait "${counselingDetail.value?.title}". Jadwal saya pada ${counselingDetail.value?.schedule?.time_slot}.`;
  const encodedMessage = encodeURIComponent(message);

  // Membersihkan nomor telepon jika ada karakter non-digit
  const cleanPhone = counselor.value.phoneNumber.replace(/\D/g, '');
  const waUrl = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;

  window.open(waUrl, '_blank');
}
</script>