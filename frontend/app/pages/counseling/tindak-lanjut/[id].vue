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
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <button @click="$router.back()"
            class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 transition flex items-center">
            <Icon name="tabler:arrow-left" class="w-6 h-6 text-gray-600" />
          </button>
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Tindak Lanjut</h1>
            <p class="text-sm text-gray-500 font-medium italic">Ref: {{ requestData.id }}</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <span
            :class="['px-4 py-1.5 rounded-full text-xs font-black uppercase border', getUrgencyClass(requestData.urgency)]">
            {{ requestData.urgency }} Priority
          </span>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-7 space-y-6">

          <div
            class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
              <img :src="requestData.student?.avatar_url"
                class="w-16 h-16 rounded-2xl object-cover border-2 border-primary-100 shadow-sm" />
              <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Siswa Pelapor</p>
                <h3 class="text-lg font-black text-gray-900">{{ requestData.student?.name }}</h3>
                <p class="text-sm text-primary-600 font-bold">{{ requestData.student?.classroom_name }} • {{
                  requestData.student?.nis }}</p>
              </div>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
              <a v-if="requestData.schedule?.method === 'chat'"
                :href="`https://wa.me/${formatPhone(requestData.student?.phone || '')}`" target="_blank"
                class="flex-1 md:flex-none px-6 py-3 bg-emerald-500 text-white rounded-2xl font-black text-xs flex items-center justify-center gap-2 hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">
                <Icon name="tabler:brand-whatsapp" class="w-5 h-5" />
                CHAT WHATSAPP
              </a>
              <NuxtLink :to="`/students/detail/${requestData.student?.id}`"
                class="p-3 bg-gray-50 text-gray-400 rounded-2xl border border-gray-100 hover:text-primary-600 transition-colors flex items-center"
                title="Lihat Profil">
                <Icon name="tabler:user-search" class="w-6 h-6" />
              </NuxtLink>
            </div>
          </div>

          <div
            class="bg-linear-to-br from-primary-600 to-primary-700 p-6 rounded-3xl text-white shadow-lg shadow-primary-100">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-4">Informasi Jadwal Aktif
            </h3>

            <div class="grid grid-cols-2 gap-6">
              <div class="space-y-1">
                <p class="text-[10px] font-bold opacity-70 uppercase">Metode Konseling</p>
                <div class="flex items-center gap-2">
                  <Icon
                    :name="requestData.schedule?.method === 'chat' ? 'tabler:message-circle-2' : 'tabler:users-group'"
                    class="w-6 h-6" />
                  <span class="font-bold text-lg leading-none">
                    {{ requestData.schedule?.method === 'chat' ? 'Online Chat' : 'Tatap Muka' }}
                  </span>
                </div>
              </div>

              <div class="space-y-1">
                <p class="text-[10px] font-bold opacity-70 uppercase">Waktu & Jam</p>
                <div class="flex items-center gap-2">
                  <Icon name="tabler:calendar-event" class="w-6 h-6" />
                  <span class="font-bold text-lg leading-none">
                    {{ formatDateFull(requestData.schedule?.schedule_date) }} — {{ requestData.schedule?.time_slot }}
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
              <span class="text-[10px] font-medium bg-white/20 px-3 py-1 rounded-full flex items-center gap-1">
                <Icon name="tabler:info-circle" class="w-3 h-3" />
                <span>Status Sesi: {{ requestData.schedule?.status.toUpperCase() }}</span>
              </span>
            </div>
          </div>

          <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-black text-primary-600 uppercase tracking-[0.2em] mb-4">Isi Laporan Siswa</h3>
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ requestData.title }}</h2>
            <div class="bg-gray-50 p-6 rounded-2xl border-l-4 border-primary-500 text-gray-700 leading-relaxed italic">
              "{{ requestData.description }}"
            </div>
          </div>
        </div>

        <div class="col-span-12 lg:col-span-5 space-y-6">
          <div class="bg-white p-8 rounded-3xl shadow-xl border border-primary-100 ring-8 ring-primary-50/50">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-black text-gray-900">Panel Intervensi</h3>
              <div class="space-x-2">
                <button type="button"
                  class="text-[10px] font-black text-yellow-600 bg-yellow-50 px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition-colors">
                  Ingatkan Siswa
                </button>
                <button type="button" @click="quickFinish"
                  class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-colors">
                  KASUS SELESAI?
                </button>
              </div>
            </div>

            <form @submit.prevent="submitFollowUp" class="space-y-6">
              <div class="space-y-2">
                <label class="text-xs font-black text-gray-500 uppercase tracking-wider">Status Keseluruhan
                  Kasus</label>
                <div class="grid grid-cols-2 gap-2">
                  <button v-for="rs in ['pending', 'scheduled', 'completed', 'rejected']" :key="rs" type="button"
                    @click="form.request_status = rs"
                    :class="['px-2 py-2 rounded-xl text-[10px] font-bold uppercase transition-all border text-center',
                      form.request_status === rs ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-gray-50 text-gray-400 border-gray-200']">
                    {{ rs }}
                  </button>
                </div>
                <p class="text-[9px] text-gray-400 italic">* Menentukan apakah laporan ini masih aktif atau sudah
                  ditolak.</p>
              </div>

              <div class="border border-gray-400">
              </div>

              <div class="space-y-2">
                <label class="text-xs font-black text-gray-500 uppercase tracking-wider">Status Sesi Pertemuan</label>
                <div class="grid grid-cols-2 gap-2">
                  <button v-for="ss in ['pending', 'confirmed', 'canceled', 'completed']" :key="ss" type="button"
                    @click="form.schedule_status = ss"
                    :class="['px-3 py-2 rounded-xl text-[10px] font-bold uppercase transition-all border',
                      form.schedule_status === ss ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-gray-50 text-gray-400 border-gray-200']">
                    {{ ss === 'completed' ? 'Selesai' : ss }}
                  </button>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-xs font-black text-gray-500 uppercase">Catatan Akhir (Resume)</label>
                <textarea v-model="form.notes" rows="4" placeholder="Tuliskan hasil akhir pembicaraan..."
                  class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary-100 outline-none transition-all resize-none"></textarea>
              </div>

              <button type="submit" :disabled="loading"
                class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black text-sm shadow-xl hover:bg-primary-700 transition-all flex items-center justify-center gap-2">
                <Icon v-if="loading" name="tabler:loader-2" class="animate-spin" />
                SIMPAN PERUBAHAN
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const loading = ref(false);
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

const form = reactive({
  request_status: requestData.value?.status || 'pending',
  schedule_status: requestData.value?.schedule?.status || 'pending',
  notes: '',
  schedule_method: requestData.value?.schedule?.method || 'chat',
  schedule_date: requestData.value?.schedule?.schedule_date || '',
  time_slot: requestData.value?.schedule?.time_slot || ''
});

function quickFinish() {
  form.request_status = 'scheduled'; // Jika sudah ada enum 'completed' di request, gunakan itu
  form.schedule_status = 'completed';
  form.notes = 'Konseling telah selesai dilaksanakan dengan baik.';
}

// 4. FITUR: Format Nomor HP untuk WA
function formatPhone(phone: string) {
  if (!phone) return '';
  // Mengubah 08... menjadi 628...
  return phone.replace(/^0/, '62');
}

async function submitFollowUp() {
  loading.value = true;
  try {
    const payload = {
      request_status: form.request_status,
      schedule_status: form.schedule_status,
      notes: form.notes,
      schedule_method: form.schedule_method,
      schedule_date: form.schedule_date,
      time_slot: form.time_slot
    };

    await useApi().patch(`/admin/counselings/${counselingId.value}`, payload);

    alert('Tindak lanjut dan pembaruan jadwal berhasil disimpan!');
    navigateTo('/counseling');
  } catch (e) {
    useToast().error('Gagal menyimpan tindak lanjut. Silakan coba lagi.');
  } finally {
    loading.value = false;
  }
}
</script>