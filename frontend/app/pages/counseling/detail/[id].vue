<template>
  <div class="space-y-6 max-w-7xl mx-auto pb-20">
    <div v-if="pending" class="flex flex-col items-center justify-center min-h-[400px] space-y-4">
      <Icon name="tabler:loader-2" class="w-12 h-12 animate-spin text-primary-600" />
      <p class="text-gray-500 font-medium">Memuat detail permintaan...</p>
    </div>

    <div v-else-if="error" class="bg-red-50 p-8 rounded-3 text-center">
      <Icon name="tabler:alert-circle" class="w-12 h-12 text-red-500 mb-2" />
      <h2 class="text-lg font-bold text-red-800">Gagal Memuat Data</h2>
      <p class="text-red-600 mb-4">{{ error.message || 'Terjadi kesalahan sistem' }}</p>
      <button @click="() => refresh()" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-bold">Coba
        Lagi</button>
    </div>

    <div v-else-if="requestData" class="space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button @click="$router.back()"
            class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 transition flex items-center">
            <Icon name="tabler:arrow-left" class="w-6 h-6 text-gray-600" />
          </button>
          <div>
            <h1 class="text-xl font-bold text-gray-800">Detail Permintaan Konseling</h1>
            <p class="text-xs text-gray-500 font-medium italic">Referesi ID: {{ requestData.id }}</p>
          </div>
        </div>

        <div class="flex gap-2">
          <button @click="printPage"
            class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-gray-50">
            <Icon name="tabler:printer" /> Cetak
          </button>
          <NuxtLink :to="`/admin/counseling/tindak-lanjut/${requestData.id}`"
            class="px-4 py-2 bg-primary-600 text-white rounded-xl text-xs font-bold hover:bg-primary-700 shadow-md">
            Edit / Tindak Lanjut
          </NuxtLink>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8 space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div :class="['h-2 w-full', getUrgencyBg(requestData.urgency)]"></div>
            <div class="p-8">
              <div class="flex justify-between items-start mb-6">
                <span
                  :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter', getStatusClass(requestData.status)]">
                  Status: {{ requestData.status }}
                </span>
                <span class="text-xs text-gray-400 font-medium">Masuk pada: {{ formatDateFull(requestData.created_at)
                }}</span>
              </div>

              <h2 class="text-2xl font-black text-gray-900 mb-4">{{ requestData.title }}</h2>
              <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                <p class="whitespace-pre-line">{{ requestData.description }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-sm font-black text-gray-900 mb-6 flex items-center gap-2">
              <Icon name="tabler:history" class="text-primary-600" />
              Log Aktivitas Kasus
            </h3>
            <div
              class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-linear-to-b before:from-gray-100 before:via-gray-100 before:to-transparent">
              <div class="relative flex items-center gap-6">
                <div
                  class="w-10 h-10 rounded-full bg-primary-50 border-4 border-white shadow-sm flex items-center justify-center z-10 text-primary-600">
                  <Icon name="tabler:plus" />
                </div>
                <div>
                  <p class="text-xs font-bold text-gray-900">Permintaan Dibuat oleh Siswa</p>
                  <p class="text-[10px] text-gray-500">{{ formatDateFull(requestData.created_at) }}</p>
                </div>
              </div>
              <div class="relative flex items-center gap-6">
                <div
                  class="w-10 h-10 rounded-full bg-amber-50 border-4 border-white shadow-sm flex items-center justify-center z-10 text-amber-600">
                  <Icon name="tabler:edit" />
                </div>
                <div>
                  <p class="text-xs font-bold text-gray-900">Pembaruan Terakhir Sistem</p>
                  <p class="text-[10px] text-gray-500">{{ formatDateFull(requestData.updated_at) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-span-12 lg:col-span-4 space-y-6">
          <div class="bg-primary-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
            <Icon name="tabler:calendar-check" class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" />

            <h3 class="text-xs font-bold uppercase tracking-widest text-primary-300 mb-6">Detail Penugasan</h3>

            <div v-if="requestData.schedule" class="space-y-6">
              <div class="flex items-center gap-4">
                <img :src="requestData.schedule.counselor.avatar_url"
                  class="w-14 h-14 rounded-2xl object-cover border-2 border-primary-700" />
                <div>
                  <p class="text-sm font-black">{{ requestData.schedule.counselor.name }}</p>
                  <p class="text-[10px] text-primary-300">{{ requestData.schedule.counselor.jabatan }}</p>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div class="bg-primary-800/50 p-3 rounded-2xl border border-primary-700">
                  <p class="text-[10px] font-bold text-primary-300 uppercase">Metode</p>
                  <p class="text-xs font-bold mt-1 capitalize">{{ requestData.schedule.method }}</p>
                </div>
                <div class="bg-primary-800/50 p-3 rounded-2xl border border-primary-700">
                  <p class="text-[10px] font-bold text-primary-300 uppercase">Jam</p>
                  <p class="text-xs font-bold mt-1">{{ requestData.schedule.time_slot }} WIB</p>
                </div>
              </div>

              <div class="bg-white/10 p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                <Icon name="tabler:calendar" class="w-6 h-6 text-primary-300" />
                <div>
                  <p class="text-[10px] text-primary-300 uppercase font-bold">Tanggal Konseling</p>
                  <p class="text-sm font-black">{{ formatDate(requestData.schedule.schedule_date) }}</p>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-6">
              <Icon name="tabler:calendar-off" class="w-12 h-12 text-primary-400 mb-2" />
              <p class="text-xs font-bold text-primary-200">Belum Ada Jadwal</p>
            </div>
          </div>

          <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Siswa</h3>
            <div class="flex items-center gap-4">
              <div class="relative shrink-0">
                <img v-if="requestData.student?.avatar_url" :src="requestData.student.avatar_url" alt="Avatar"
                  class="w-12 h-12 rounded-full object-cover border border-gray-100 shadow-sm"
                  @error="(e) => (e.target as HTMLImageElement).src = '/static/images/profile.png'">

                <div v-else
                  class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 border border-primary-200">
                  {{ requestData.student?.name?.charAt(0).toUpperCase() || 'S' }}
                </div>
              </div>

              <div>
                <p class="text-sm font-bold text-gray-900 leading-none mb-1">
                  {{ requestData.student?.name || 'Siswa' }}
                </p>
                <NuxtLink :to="`/students/detail/${requestData.student?.id}`"
                  class="text-[11px] text-primary-600 hover:text-primary-700 font-bold flex items-center gap-1">
                  Buka Profil Lengkap
                  <Icon name="tabler:arrow-narrow-right" class="w-3 h-3" />
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// 2. State & Fetching
const route = useRoute();
const counselingId = computed(() => route.params.id as string);

const { data: requestData, pending, error, refresh } = await useAsyncData(
  `counseling-${counselingId.value}`, // Gunakan .value untuk key
  async () => {
    try {
      const res = await useApi().get<CounselingRequest>(`/admin/counselings/${counselingId.value}`);

      if (!res.status || !res.data) {
        throw createError({ statusCode: 404, statusMessage: 'Data tidak ditemukan' });
      }

      return res.data;
    } catch (e: any) {
      throw e;
    }
  }
);

// 3. Helpers
function getUrgencyBg(urg: string) {
  const map: Record<string, string> = {
    high: 'bg-red-500',
    medium: 'bg-orange-500',
    low: 'bg-blue-500'
  };
  return map[urg] || 'bg-primary-500';
}

function getStatusClass(status: string) {
  const map: Record<string, string> = {
    scheduled: 'bg-primary-100 text-primary-700',
    finished: 'bg-emerald-100 text-emerald-700',
    pending: 'bg-amber-100 text-amber-700'
  };
  return map[status] || 'bg-gray-100 text-gray-600';
}

function formatDate(dateStr: string) {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function formatDateFull(dateStr: string) {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleString('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
  });
}

function printPage() {
  window.print();
}
</script>

<style scoped>
@media print {

  button,
  .NuxtLink {
    display: none !important;
  }
}
</style>