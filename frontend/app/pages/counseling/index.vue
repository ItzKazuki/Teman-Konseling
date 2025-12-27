<template>
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Konseling</h1>
        <p class="text-sm text-gray-500">Pantau dan tindak lanjuti permintaan konseling siswa</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="flex bg-white border border-gray-200 p-1 rounded-xl shadow-sm">
          <button v-for="tab in ['all', 'pending', 'scheduled', 'rejected', 'completed']" :key="tab" @click="activeTab = tab" :class="['px-4 py-1.5 rounded-lg text-xs font-bold transition-all capitalize',
            activeTab === tab ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-gray-50']">
            {{ tab }}
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-red-100 text-red-600 rounded-lg flex items-center">
          <Icon name="tabler:alert-octagon" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Urgency Tinggi</p>
          <p class="text-xl font-bold text-gray-800">{{ stats.highUrgency }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-primary-100 text-primary-600 rounded-lg flex items-center">
          <Icon name="tabler:calendar-event" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Terjadwal Minggu Ini</p>
          <p class="text-xl font-bold text-gray-800">{{ stats.scheduled }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-amber-100 text-amber-600 rounded-lg flex items-center">
          <Icon name="tabler:clock-pause" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Menunggu Konfirmasi</p>
          <p class="text-xl font-bold text-gray-800">{{ stats.pending }}</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
      <div v-for="item in filteredRequests" :key="item.id"
        class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex flex-col lg:flex-row gap-6">

          <div class="flex-1 space-y-3">
            <div class="flex items-center gap-2">
              <span
                :class="['px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider', getUrgencyClass(item.urgency)]">
                {{ item.urgency }} Priority
              </span>
              <span
                :class="['px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider', getStatusClass(item.status)]">
                {{ item.status }}
              </span>
            </div>

            <div>
              <h3 class="text-lg font-bold text-gray-900">{{ item.title }}</h3>
              <p class="text-sm text-gray-600 line-clamp-2 mt-1 italic">"{{ item.description }}"</p>
            </div>

            <div class="flex items-center gap-4 text-xs text-gray-500 mt-4">
              <span class="flex items-center gap-1">
                <Icon name="tabler:user" /> Nama Siswa: {{ item.student.name }}
              </span>
              <span class="flex items-center gap-1">
                <Icon name="tabler:calendar-plus" /> Dibuat: {{ formatDateFull(item.created_at) }}
              </span>
            </div>
          </div>

          <div class="lg:w-80 bg-gray-50 rounded-xl p-4 border border-gray-100">
            <div v-if="item.schedule" class="space-y-3">
              <div class="flex items-center gap-3">
                <img :src="item.schedule.counselor.avatar_url"
                  class="w-8 h-8 rounded-full border border-white shadow-sm" />
                <div>
                  <p class="text-xs font-bold text-gray-900">{{ item.schedule.counselor.name }}</p>
                  <p class="text-[10px] text-gray-500">{{ item.schedule.counselor.jabatan }}</p>
                </div>
              </div>

              <div class="pt-3 border-t border-gray-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-500">Metode:</span>
                  <span class="font-bold flex items-center gap-1">
                    <Icon :name="item.schedule.method === 'chat' ? 'tabler:messages' : 'tabler:users-group'"
                      class="text-primary-600" />
                    {{ item.schedule.method === 'chat' ? 'Online Chat' : 'Tatap Muka' }}
                  </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-500">Waktu:</span>
                  <span class="font-bold text-gray-900">{{ formatDateFull(item.schedule.schedule_date) }} | {{
                    item.schedule.time_slot }}</span>
                </div>
              </div>
            </div>

            <div v-else class="h-full flex flex-col items-center justify-center text-center py-4">
              <Icon name="tabler:calendar-off" class="text-gray-300 w-8 h-8 mb-2" />
              <p class="text-xs text-gray-500">Jadwal Belum Dibuat</p>
            </div>
          </div>

          <div class="flex lg:flex-col gap-2 justify-center lg:w-40">
            <NuxtLink v-if="item.schedule" :to="`/counseling/tindak-lanjut/${item.id}`"
              class="flex-1 lg:flex-none py-2 px-4 bg-primary-600 text-white rounded-lg text-xs font-bold hover:bg-primary-700 transition">
              Tindak Lanjut
            </NuxtLink>
            <NuxtLink :to="`/counseling/${item.id}`"
              class="flex-1 lg:flex-none py-2 px-4 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50">
              Lihat Detail
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const counselingRequests = ref<CounselingRequest[]>([]);
const isLoading = ref<boolean>(false);
const activeTab = ref('all');

// Stats dihitung secara dinamis
const stats = computed(() => {
  return {
    highUrgency: counselingRequests.value.filter(r => r.urgency === 'high').length,
    scheduled: counselingRequests.value.filter(r => r.status === 'scheduled').length,
    pending: counselingRequests.value.filter(r => r.status === 'pending').length,
  };
});

// Filter Tab
const filteredRequests = computed(() => {
  if (activeTab.value === 'all') return counselingRequests.value;
  return counselingRequests.value.filter(r => r.status === activeTab.value);
});

async function getAllArticle() {
  isLoading.value = true;
  const resCounseling = await useApi().get<CounselingRequest[]>('/admin/counselings');

  if (resCounseling.status && resCounseling.data) {
    counselingRequests.value = resCounseling.data;
  } else {
    counselingRequests.value = [];
  }
  isLoading.value = false;
}

onMounted(() => {
  getAllArticle();
})
</script>