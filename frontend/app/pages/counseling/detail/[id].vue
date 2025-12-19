<template>
  <div class="space-y-6 pb-10">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <button @click="$router.back()" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 transition flex items-center">
          <Icon name="tabler:arrow-left" class="w-6 h-6 text-gray-600" />
        </button>
        <div>
          <h1 class="text-xl font-bold text-gray-800">Detail Permintaan Konseling</h1>
          <p class="text-xs text-gray-500 font-medium italic">Referesi ID: {{ requestData.id }}</p>
        </div>
      </div>
      
      <div class="flex gap-2">
        <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-gray-50">
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
              <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter', getStatusClass(requestData.status)]">
                Status: {{ requestData.status }}
              </span>
              <span class="text-xs text-gray-400 font-medium">Masuk pada: {{ formatDateFull(requestData.created_at) }}</span>
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
          <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-linear-to-b before:from-gray-100 before:via-gray-100 before:to-transparent">
            <div class="relative flex items-center gap-6">
              <div class="w-10 h-10 rounded-full bg-primary-50 border-4 border-white shadow-sm flex items-center justify-center z-10 text-primary-600">
                <Icon name="tabler:plus" />
              </div>
              <div>
                <p class="text-xs font-bold text-gray-900">Permintaan Dibuat oleh Siswa</p>
                <p class="text-[10px] text-gray-500">{{ formatDateFull(requestData.created_at) }}</p>
              </div>
            </div>
            <div class="relative flex items-center gap-6">
              <div class="w-10 h-10 rounded-full bg-amber-50 border-4 border-white shadow-sm flex items-center justify-center z-10 text-amber-600">
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
              <img :src="requestData.schedule.counselor.avatar_url" class="w-14 h-14 rounded-2xl object-cover border-2 border-primary-700" />
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
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500">
              S
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">ID Siswa: {{ requestData.student_id.slice(0, 8) }}</p>
              <NuxtLink :to="`/admin/students/${requestData.student_id}`" class="text-[10px] text-primary-600 hover:underline font-bold">
                Buka Profil Lengkap →
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();

// Mock data menggunakan struktur JSON Anda
const requestData = ref({
  id: "019b26b8-d485-70d6-bcef-bca9204744c7",
  student_id: "019b14a8-421f-73de-b074-1b21f66dbe5c",
  title: "Saya takut ga lolos utbk",
  description: "Jekekej rjrjjee ejeje ejejeusoxh eisuxjebekejennejej djdn. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
  urgency: "medium",
  status: "scheduled",
  created_at: "2025-12-16T10:33:25.000000Z",
  updated_at: "2025-12-16T10:33:33.000000Z",
  schedule: {
    method: "face-to-face",
    schedule_date: "2025-12-25T00:00:00.000000Z",
    time_slot: "14:00",
    counselor: {
      name: "Ibu Lia Permata",
      jabatan: "Koordinator BK",
      avatar_url: "http://api.teman-konseling.test/static/profile.png"
    }
  }
});

// Helpers
function getUrgencyBg(urg: string) {
  if (urg === 'high') return 'bg-red-500';
  if (urg === 'medium') return 'bg-orange-500';
  return 'bg-primary-500';
}

function getStatusClass(status: string) {
  if (status === 'scheduled') return 'bg-primary-100 text-primary-700';
  if (status === 'finished') return 'bg-emerald-100 text-emerald-700';
  return 'bg-amber-100 text-amber-700';
}

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function formatDateFull(dateStr: string) {
  return new Date(dateStr).toLocaleString('id-ID', { 
    day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' 
  });
}
</script>