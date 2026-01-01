<template>
  <div class="space-y-8 pb-24 sm:pb-10">
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
        <p class="text-sm text-gray-500">Menyiapkan detail pertemuan tatap muka...</p>
      </div>
    </div>

    <template v-else-if="counselingDetail">
      <div class="flex items-center space-x-4 pb-2 border-b border-gray-100">
        <Icon name="mdi:account-group" class="w-7 h-7 text-primary-600" />
        <h1 class="text-2xl font-bold text-gray-900">Konseling Tatap Muka</h1>
      </div>

      <div class="bg-linear-to-br from-primary-600 to-primary-700 p-6 rounded-2xl shadow-xl text-white space-y-4">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-primary-100 text-sm font-medium uppercase tracking-wider">Lokasi Pertemuan</p>
            <h2 class="text-xl font-bold flex items-center gap-2 mt-1">
              <Icon name="mdi:map-marker-radius" class="w-6 h-6" />
              {{ counselingDetail.location || 'Ruang Konseling BK' }}
            </h2>
          </div>
          <Icon name="mdi:calendar-check" class="w-10 h-10 opacity-30" />
        </div>
        
        <div class="flex gap-4 pt-2 border-t border-white/20">
          <div>
            <p class="text-primary-100 text-xs">Waktu</p>
            <p class="font-bold text-lg">{{ counselingDetail.schedule?.time_slot }}</p>
          </div>
          <div class="border-l border-white/20 pl-4">
            <p class="text-primary-100 text-xs">Tanggal</p>
            <p class="font-bold text-lg">{{ formatDate(counselingDetail.schedule?.schedule_date) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
          <Icon name="mdi:account-tie" class="w-5 h-5 text-primary-600" />
          Konselor Pendamping
        </h3>
        <div class="flex items-center space-x-4">
          <img :src="counselor.photoUrl" :alt="counselor.name"
            class="w-14 h-14 object-cover rounded-full ring-2 ring-gray-50" />
          <div>
            <p class="font-bold text-gray-900">{{ counselor.name }}</p>
            <p class="text-sm text-gray-500 italic">{{ counselor.jabatan }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 p-4 rounded-xl border-l-4 border-primary-500">
        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Topik Bahasan:</p>
        <p class="text-gray-800 font-medium">"{{ counselingDetail.title }}"</p>
      </div>

      <div class="space-y-4">
        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
          <Icon name="mdi:information-outline" class="w-5 h-5 text-primary-600" />
          Hal yang Perlu Diperhatikan
        </h2>
        <ul class="space-y-3 pl-1">
          <li class="flex items-start gap-3 text-sm text-gray-700">
            <Icon name="mdi:check-circle" class="w-5 h-5 text-green-500 mt-0.5 flex-0" />
            <span>Datang tepat waktu (minimal 5 menit sebelum jadwal).</span>
          </li>
          <li class="flex items-start gap-3 text-sm text-gray-700">
            <Icon name="mdi:check-circle" class="w-5 h-5 text-green-500 mt-0.5 flex-0" />
            <span>Jika berhalangan hadir, harap segera hubungi konselor.</span>
          </li>
          <li class="flex items-start gap-3 text-sm text-gray-700">
            <Icon name="mdi:check-circle" class="w-5 h-5 text-green-500 mt-0.5 flex-0" />
            <span>Siapkan hal-hal yang ingin dikonsultasikan secara terbuka.</span>
          </li>
        </ul>
      </div>

      <div class="fixed inset-x-0 bottom-0 p-4 z-100 bg-white border-t border-gray-100 
                  sm:static sm:p-0 sm:border-none">
        <button @click="confirmArrival" class="w-full py-4 bg-primary-600 text-white rounded-xl font-bold text-lg 
                 hover:bg-primary-700 transition duration-200 shadow-lg shadow-primary-500/30 
                 flex items-center justify-center gap-3">
          <Icon name="mdi:check-all" class="w-6 h-6" />
          Saya Akan Hadir
        </button>
      </div>
    </template>

    <div v-else class="text-center py-20 text-red-500 font-medium">
      Gagal memuat detail pertemuan.
    </div>
  </div>
</template>

<script setup>
const route = useRoute();
const id = route.params.id;

const isLoading = ref(true);
const counselingDetail = ref(null);

const counselor = computed(() => {
  const data = counselingDetail.value?.schedule?.counselor;
  return {
    name: data?.name || "Memuat...",
    photoUrl: data?.avatar_url || "/static/images/profile.png",
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
    console.error('Error:', error);
  } finally {
    isLoading.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};

onMounted(() => {
  fetchData();
});

function confirmArrival() {
  useToast().success("Kehadiran Anda telah dikonfirmasi oleh sistem.");
  // Tambahkan logika API untuk konfirmasi kehadiran jika ada
}
</script>