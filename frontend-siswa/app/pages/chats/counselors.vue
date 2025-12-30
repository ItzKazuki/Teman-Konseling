<template>
  <div class="space-y-8 max-w-lg mx-auto">

    <AppHeader title="Pilih Konselor Anda" icon="tk:chat-bold" />

    <div v-if="requestData" :class="['p-4 rounded-xl shadow-md border-l-4', urgencyClass]">
      <div class="flex items-center space-x-3">
        <Icon name="tabler:info-circle" class="w-6 h-6 shrink-0" :class="urgencyTextClass" />
        <div>
          <h2 class="font-bold" :class="urgencyTextClass">Permintaan Anda Siap Dilanjutkan</h2>
          <p class="text-sm text-gray-700">
            Masalah: <span class="font-semibold">{{ requestData.title }}</span>. Urgensi: <span
              :class="urgencyTextClass">{{ getUrgencyLabel(requestData.urgency) }}</span>.
          </p>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-5 text-gray-500">
      <p>Memuat detail permintaan...</p>
    </div>

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
            <img :src="counselor.avatar_url" :alt="`Foto ${counselor.name}`"
              class="w-16 h-16 object-cover rounded-full border-2 border-primary-200 shrink-0" />

            <div class="grow">
              <h3 class="text-lg font-bold text-gray-900">{{ counselor.name }}</h3>
              <p class="text-sm text-gray-600">{{ counselor.title }}</p>

              <div class="flex items-center mt-1">
                <span
                  :class="['w-2.5 h-2.5 rounded-full mr-2', counselor.is_available ? 'bg-green-500' : 'bg-red-500']"></span>
                <p :class="['text-xs font-semibold', counselor.is_available ? 'text-green-600' : 'text-red-600']">
                  {{ counselor.is_available ? 'Menerima Jadwal' : 'Penuh Hari Ini' }}
                </p>
              </div>

              <div class="mt-3">
                <button @click="scheduleConsultation(counselor)" :disabled="!counselor.is_available" :class="[
                  'py-2 px-4 rounded-lg font-semibold text-sm transition duration-150',
                  counselor.is_available
                    ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm'
                    : 'bg-gray-200 text-gray-500 cursor-not-allowed'
                ]">
                  Pilih & Buat Jadwal
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-10 text-gray-500">
        <p>Saat ini tidak ada Guru BK yang tersedia untuk membuat jadwal.</p>
        <p class="mt-2">Silakan coba beberapa saat lagi atau cek jadwal Anda nanti.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute();
const isLoading = ref(true);
const availableCounselors = ref([]);
const requestData = ref(null); // Data permintaan yang baru diajukan

// Computed untuk penyesuaian tampilan berdasarkan urgensi
const urgencyClass = computed(() => {
  if (!requestData.value) return 'bg-gray-50 border-gray-400';
  switch (requestData.value.urgency) {
    case 'low': return 'bg-green-50 border-green-400';
    case 'medium': return 'bg-yellow-50 border-yellow-400';
    case 'high': return 'bg-red-50 border-red-400';
    default: return 'bg-gray-50 border-gray-400';
  }
});

const urgencyTextClass = computed(() => {
  if (!requestData.value) return 'text-gray-700';
  switch (requestData.value.urgency) {
    case 'low': return 'text-green-700';
    case 'medium': return 'text-yellow-700';
    case 'high': return 'text-red-700';
    default: return 'text-gray-700';
  }
});

const getUrgencyLabel = (urgency) => {
  switch (urgency) {
    case 'low': return 'Rendah';
    case 'medium': return 'Sedang';
    case 'high': return 'Tinggi';
    default: return 'Tidak Diketahui';
  }
};


const fetchData = async () => {
  isLoading.value = true;

  try {
    const requestId = route.query.requestId;
    // Ambil detail permintaan konseling berdasarkan requestId
    const requestRes = await useApi().get(`/student/counseling/${requestId}`);
    if (requestRes.status && requestRes.data) {
      requestData.value = requestRes.data;
    }

    const listCounselors = await useApi().get('/reference/counselors');
    if (listCounselors.status && listCounselors.data) {
      // availableCounselors.value = listCounselors.data.filter(c => c.is_available);
      availableCounselors.value = listCounselors.data;
    }
  } catch (err) {
    console.error('Error fetching counselors:', err);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  if (!route.query.requestId) {
    // Jika tidak ada requestId, pengguna mungkin langsung mengakses halaman ini
    useToast().error("Anda harus mengajukan permintaan konseling terlebih dahulu.");
    navigateTo('/chats/new-request');
    return;
  }
  fetchData();
});

function scheduleConsultation(counselor) {
  if (!counselor.is_available) {
    useToast().error(`Maaf, ${counselor.name} sedang penuh jadwal.`);
    return;
  }

  // Mengarahkan ke halaman jadwal spesifik dan membawa data konselor + data permintaan
  navigateTo({
    path: '/chats/schedule/form',
    query: {
      counselorId: counselor.id,
      counselorName: counselor.name,
      // Penting: Kirim juga Request ID agar formulir jadwal tahu permintaan mana yang sedang dijadwalkan
      requestId: requestData.value.id,
    }
  });

  console.log(`Mengarahkan ke halaman jadwal untuk ${counselor.name}.`);
}
</script>