<template>
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Emosi Siswa</h1>
        <p class="text-sm text-gray-500">Telusuri dan ekspor seluruh data aktivitas emosi siswa</p>
      </div>
      
      <div class="flex items-center gap-2">
        <button @click="exportData('excel')" class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm font-bold hover:bg-emerald-100 transition">
          <Icon name="tabler:file-spreadsheet" class="w-5 h-5" />
          Ekspor Excel
        </button>
        <button @click="exportData('pdf')" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm font-bold hover:bg-red-100 transition">
          <Icon name="tabler:file-type-pdf" class="w-5 h-5" />
          Cetak PDF
        </button>
      </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="space-y-1">
          <label class="text-xs font-bold text-gray-500 uppercase">Cari Siswa</label>
          <div class="relative">
            <Icon name="tabler:search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input v-model="filters.search" type="text" placeholder="Nama atau NISN..." 
              class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 outline-none" />
          </div>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-gray-500 uppercase">Kelas</label>
          <select v-model="filters.classroom" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="">Semua Kelas</option>
            <option v-for="cls in classrooms" :key="cls" :value="cls">{{ cls }}</option>
          </select>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-gray-500 uppercase">Dari Tanggal</label>
          <input v-model="filters.date_from" type="date" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 outline-none" />
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-gray-500 uppercase">Sampai Tanggal</label>
          <input v-model="filters.date_to" type="date" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 outline-none" />
        </div>
      </div>
      
      <div class="mt-4 flex justify-end">
        <button @click="resetFilters" class="text-sm text-gray-500 hover:text-primary-600 font-medium">Reset Filter</button>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Siswa</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Emosi</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Intensitas</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Cerita</th>
              <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="mood in moodHistory" :key="mood.id" class="hover:bg-gray-50/50 transition">
              <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-medium">
                {{ mood.date }}
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-primary-600">
                    {{ mood.student_name.charAt(0) }}
                  </div>
                  <div>
                    <div class="text-sm font-bold text-gray-900">{{ mood.student_name }}</div>
                    <div class="text-[10px] text-gray-400">{{ mood.classroom }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span :class="['px-2.5 py-1 rounded-full text-[11px] font-bold', getEmotionClass(mood.emotion_name)]">
                  {{ mood.emotion_name }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-1">
                  <div v-for="i in 4" :key="i" :class="['h-1 w-3 rounded-full', i <= mood.magnitude ? getMagnitudeColor(mood.magnitude, mood.emotion_name) : 'bg-gray-200']"></div>
                </div>
              </td>
              <td class="px-6 py-4 max-w-xs">
                <p class="text-xs text-gray-500 truncate italic">"{{ mood.story || '-' }}"</p>
              </td>
              <td class="px-6 py-4 text-right">
                <NuxtLink :to="`/admin/students/${mood.student_id}`" class="p-2 text-gray-400 hover:text-primary-600">
                  <Icon name="tabler:eye" class="w-5 h-5" />
                </NuxtLink>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
        <span class="text-xs text-gray-500">Menampilkan 1-10 dari 240 data</span>
        <div class="flex gap-2">
          <button class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-medium disabled:opacity-50">Prev</button>
          <button class="px-3 py-1 bg-primary-600 text-white border border-primary-600 rounded text-xs font-medium">Next</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// State filter
const filters = reactive({
  search: '',
  classroom: '',
  date_from: '',
  date_to: ''
})

const classrooms = ['X-RPL 1', 'X-RPL 2', 'XI-RPL 1', 'XI-RPL 2', 'XII-RPL 1'];

// Mock Data
const moodHistory = ref([
  { id: 1, student_id: 's1', student_name: 'Zahra Amelia', classroom: 'XII-RPL 1', emotion_name: 'Sedih', magnitude: 3, story: 'Lagi kangen rumah...', date: '18 Des 2025' },
  { id: 2, student_id: 's2', student_name: 'Raka Pratama', classroom: 'XII-RPL 1', emotion_name: 'Bangga', magnitude: 4, story: 'Berhasil fix bug di project!', date: '18 Des 2025' },
  // ... lebih banyak data
]);

function resetFilters() {
  filters.search = '';
  filters.classroom = '';
  filters.date_from = '';
  filters.date_to = '';
}

function exportData(type: 'excel' | 'pdf') {
  alert(`Menyiapkan file ${type.toUpperCase()} berdasarkan filter...`);
  // Logika redirect ke endpoint ekspor backend
  // window.location.href = `/api/v1/admin/export/moods?type=${type}&...params`;
}

// Helpers
function getEmotionClass(name: string) {
  if (['Sedih', 'Marah', 'Takut'].includes(name)) return 'bg-red-50 text-red-600 border border-red-100';
  return 'bg-emerald-50 text-emerald-600 border border-emerald-100';
}

function getMagnitudeColor(mag: number, name: string) {
  if (['Sedih', 'Marah', 'Takut'].includes(name)) return 'bg-red-500';
  return 'bg-emerald-500';
}
</script>