<template>
  <div class="space-y-6 max-w-7xl mx-auto pb-20">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <button @click="$router.back()" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 transition flex items-center">
          <Icon name="tabler:arrow-left" class="w-6 h-6 text-gray-600" />
        </button>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tindak Lanjut Konseling</h1>
          <p class="text-sm text-gray-500 font-medium">Selesaikan penanganan kasus untuk membantu siswa</p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span :class="['px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border', getUrgencyClass(requestData.urgency)]">
          {{ requestData.urgency }} Priority
        </span>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
      <div class="col-span-12 lg:col-span-7 space-y-6">
        
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
          <div class="absolute top-0 right-0 p-8 opacity-5">
            <Icon name="tabler:quote" class="w-24 h-24" />
          </div>
          
          <div class="relative z-10">
            <h3 class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-2">Laporan Permasalahan</h3>
            <h2 class="text-2xl font-black text-gray-900 leading-tight mb-4">{{ requestData.title }}</h2>
            <div class="bg-gray-50 p-6 rounded-2xl border-l-4 border-primary-500 italic text-gray-700 leading-relaxed text-lg">
              "{{ requestData.description }}"
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-gray-50">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase">Metode Pertemuan</p>
              <p class="text-sm font-bold text-gray-800 flex items-center gap-2 mt-1">
                <Icon :name="requestData.schedule?.method === 'chat' ? 'tabler:message-circle' : 'tabler:users'" class="text-primary-500" />
                {{ requestData.schedule?.method === 'chat' ? 'Online Chat' : 'Tatap Muka' }}
              </p>
            </div>
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase">Waktu Terjadwal</p>
              <p class="text-sm font-bold text-gray-800 flex items-center gap-2 mt-1">
                <Icon name="tabler:calendar-time" class="text-primary-500" />
                {{ requestData.schedule ? formatDate(requestData.schedule.schedule_date) : 'Belum Dijadwalkan' }}
              </p>
            </div>
          </div>
        </div>

        <div v-if="requestData.schedule?.counselor" class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center gap-4">
          <img :src="requestData.schedule.counselor.avatar_url" class="w-12 h-12 rounded-full border-2 border-primary-100" />
          <div>
            <p class="text-xs text-gray-500">Konselor Penanggung Jawab:</p>
            <p class="text-sm font-bold text-gray-800">{{ requestData.schedule.counselor.name }} ({{ requestData.schedule.counselor.jabatan }})</p>
          </div>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-5 space-y-6">
        <div class="bg-white p-8 rounded-3xl shadow-xl border border-primary-100 ring-8 ring-primary-50/50">
          <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <Icon name="tabler:clipboard-text" class="text-primary-600 w-6 h-6" />
            Hasil Intervensi
          </h3>

          <form @submit.prevent="submitFollowUp" class="space-y-6">
            <div class="space-y-2">
              <label class="text-xs font-black text-gray-500 uppercase tracking-wider">Status Akhir Kasus</label>
              <div class="grid grid-cols-2 gap-2">
                <button 
                  v-for="status in ['scheduled', 'ongoing', 'finished', 'cancelled']" 
                  :key="status"
                  type="button"
                  @click="form.status = status"
                  :class="['px-3 py-2 rounded-xl text-[10px] font-bold uppercase transition-all border', 
                           form.status === status ? 'bg-primary-600 text-white border-primary-600' : 'bg-gray-50 text-gray-500 border-gray-200 hover:border-primary-300']"
                >
                  {{ status }}
                </button>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-xs font-black text-gray-500 uppercase tracking-wider">Catatan Konseling (Rahasia)</label>
              <textarea 
                v-model="form.notes"
                rows="8" 
                placeholder="Tuliskan hasil observasi, diagnosa sementara, dan langkah tindak lanjut yang disarankan..."
                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none transition-all resize-none"
              ></textarea>
            </div>

            <div class="flex gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-100">
              <Icon name="tabler:shield-lock" class="text-amber-600 shrink-0 w-6 h-6" />
              <p class="text-[10px] text-amber-800 leading-relaxed font-medium">
                Sesuai kode etik konselor, catatan ini bersifat <strong>RAHASIA</strong>. Hanya Anda dan pihak berwenang (Kepala Sekolah/Koordinator BK) yang dapat mengakses data ini.
              </p>
            </div>

            <button 
              type="submit" 
              :disabled="loading"
              class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black text-sm shadow-lg shadow-primary-200 hover:bg-primary-700 active:scale-95 transition-all flex items-center justify-center gap-2"
            >
              <Icon v-if="loading" name="tabler:loader-2" class="animate-spin" />
              SIMPAN TINDAK LANJUT
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const loading = ref(false);

// Data Mockup (Nanti diganti API call berdasarkan ID)
const requestData = ref({
  id: route.params.id,
  title: "Takut sama masa depan pak",
  description: "Bisa kesurupan jerome polin gak, sjhdbdhdhdbhxhxhdudhdbdjdnncncjfjcjchchchhhccjcjcncbfhdjjfjdjfjhfbf",
  urgency: "high",
  status: "scheduled",
  schedule: {
    method: "chat",
    schedule_date: "2025-12-17",
    time_slot: "14:00",
    counselor: {
      name: "Ibu Lia Permata",
      jabatan: "Koordinator BK",
      avatar_url: "http://api.teman-konseling.test/static/profile.png"
    }
  }
});

const form = reactive({
  status: requestData.value.status,
  notes: ''
});

function getUrgencyClass(urg: string) {
  if (urg === 'high') return 'bg-red-50 text-red-700 border-red-200';
  if (urg === 'medium') return 'bg-orange-50 text-orange-700 border-orange-200';
  return 'bg-primary-50 text-primary-700 border-primary-200';
}

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
  });
}

async function submitFollowUp() {
  loading.value = true;
  // API Call: PATCH /api/v1/admin/counseling/{id}
  await new Promise(resolve => setTimeout(resolve, 1500));
  alert('Tindak lanjut berhasil disimpan!');
  loading.value = false;
  navigateTo('/admin/counseling');
}
</script>