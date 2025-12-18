<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <button @click="$router.back()"
          class="p-2 bg-white flex items-center rounded-lg shadow-sm border border-gray-100 hover:bg-gray-50">
          <Icon name="tabler:arrow-left" class="w-5 h-5 text-gray-600" />
        </button>
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Detail Perkembangan Siswa</h1>
          <p class="text-sm text-gray-500">Memantau kesehatan mental dan riwayat emosi siswa</p>
        </div>
      </div>
      <div class="flex gap-2">
        <button
          class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-50">
          <Icon name="tabler:printer" /> Cetak Laporan
        </button>
        <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700">
          Atur Jadwal Konseling
        </button>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
      <div class="col-span-12 lg:col-span-4 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <div class="flex flex-col items-center text-center mb-6">
            <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mb-4">
              <Icon name="tabler:user" class="w-12 h-12 text-primary-600" />
            </div>
            <h3 class="text-xl font-bold text-gray-900">{{ student.name }}</h3>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold mt-2">
              {{ student.classroom }}
            </span>
          </div>

          <div class="space-y-4 border-t border-gray-50 pt-6">
            <div class="flex items-center gap-3 text-sm">
              <Icon name="tabler:id" class="text-gray-400" />
              <span class="text-gray-500 w-24 text-xs">NISN</span>
              <span class="font-medium text-gray-800">{{ student.nisn }}</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
              <Icon name="tabler:phone" class="text-gray-400" />
              <span class="text-gray-500 w-24 text-xs">No. HP</span>
              <span class="font-medium text-gray-800">{{ student.phone }}</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
              <Icon name="tabler:map-pin" class="text-gray-400" />
              <span class="text-gray-500 w-24 text-xs">Alamat</span>
              <span class="font-medium text-gray-800">{{ student.address }}</span>
            </div>
          </div>

          <div class="mt-8">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Kontak Darurat</h4>
            <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
              <p class="text-sm font-bold text-orange-800">{{ student.parent_name }} (Orang Tua)</p>
              <p class="text-xs text-orange-600 mt-1">{{ student.parent_phone }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-8 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Tren Intensitas Emosi (30 Hari Terakhir)</h3>
            <div class="flex gap-2">
              <span class="flex items-center gap-1 text-xs text-gray-500">
                <div class="w-3 h-3 bg-primary-500 rounded-full"></div> Intensitas
              </span>
            </div>
          </div>

          <div class="h-[300px]">
            <Line v-if="!isLoading" :data="chartData" :options="chartOptions" />
          </div>
        </div>
      </div>

      <div class="col-span-12">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-800 mb-6">Riwayat Cerita & Emosi</h3>

          <div class="relative border-l-2 border-gray-100 ml-3 space-y-8">
            <div v-for="mood in student.mood_history" :key="mood.id" class="relative pl-8">
              <div
                :class="['absolute -left-[9px] top-1 w-4 h-4 rounded-full border-4 border-white shadow-sm', getMagnitudeColor(mood.magnitude, mood.emotion_name)]">
              </div>

              <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <div class="flex justify-between items-start mb-3">
                  <div class="flex items-center gap-3">
                    <span :class="['px-3 py-1 rounded-full text-xs font-bold', getEmotionClass(mood.emotion_name)]">
                      {{ mood.emotion_name }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">{{ mood.formatted_date }}</span>
                  </div>
                  <div class="flex gap-1">
                    <div v-for="i in 4" :key="i"
                      :class="['w-3 h-1 rounded-full', i <= mood.magnitude ? getMagnitudeColor(mood.magnitude, mood.emotion_name) : 'bg-gray-200']">
                    </div>
                  </div>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed italic">
                  "{{ mood.story || 'Siswa tidak menuliskan cerita.' }}"
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler)

const route = useRoute()
const isLoading = ref(true)

// Mock Data (Ganti dengan API Call)
const student = ref<StudentDetail>({
  name: '',
  nisn: '',
  classroom: '',
  phone: '',
  address: '',
  parent_name: '',
  parent_phone: '',
  mood_history: [],
  chart_data: [],
  chart_labels: []
})

// Konfigurasi Chart
const chartData = computed(() => ({
  labels: ['12 Des', '13 Des', '14 Des', '15 Des', '16 Des', '17 Des', '18 Des'],
  datasets: [{
    label: 'Tingkat Intensitas',
    data: [1, 2, 1, 3, 4, 3, 4],
    borderColor: '#4f46e5',
    backgroundColor: 'rgba(79, 70, 229, 0.1)',
    tension: 0.4,
    fill: true,
    pointRadius: 5,
    pointBackgroundColor: '#4f46e5'
  }]
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: { min: 0, max: 4, ticks: { stepSize: 1 } }
  },
  plugins: {
    legend: { display: false }
  }
}

// Helpers (Gunakan yang sama dengan dashboard sebelumnya)
function getEmotionClass(name: string) {
  if (['Sedih', 'Marah', 'Takut'].includes(name)) return 'bg-red-100 text-red-700';
  return 'bg-emerald-100 text-emerald-700';
}

function getMagnitudeColor(mag: number, name: string) {
  if (['Sedih', 'Marah', 'Takut'].includes(name)) return 'bg-red-500';
  return 'bg-emerald-500';
}

onMounted(async () => {
  isLoading.value = true;
  const res = await useApi().get<StudentDetail>(`/admin/students/details/${route.params.id}`)
  if (res.status && res.data) {
    student.value = res.data
  }
  isLoading.value = false
})
</script>