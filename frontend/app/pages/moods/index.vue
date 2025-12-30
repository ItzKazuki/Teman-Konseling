<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-primary-100 rounded-full flex items-center text-primary-600">
            <Icon name="tabler:users" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-2xl font-bold">{{ moodStudentData.stats.total_students }}</div>
            <div class="text-xs text-gray-500">Total Siswa</div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-green-100 rounded-full flex items-center text-green-600">
            <Icon name="tabler:user-check" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-2xl font-bold">{{ moodStudentData.stats.filled_today }}</div>
            <div class="text-xs text-gray-500">Check-in Hari Ini</div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-orange-100 rounded-full flex items-center text-orange-600">
            <Icon name="tabler:activity" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-2xl font-bold">{{ moodStudentData.stats.avg_stress_level }}</div>
            <div class="text-xs text-gray-500">Indeks Intensitas</div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-red-100 rounded-full flex items-center text-red-600">
            <Icon name="tabler:alert-triangle" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-2xl font-bold">{{ moodStudentData.stats.vulnerable_count }}</div>
            <div class="text-xs text-gray-500">Siswa Rentan</div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="can(['bk', 'guru'])" class="col-span-12 lg:col-span-8 space-y-6">
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-gray-800">Log Emosi Harian</h3>
          <div class="flex gap-2">
            <select v-model="filterStatus"
              class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-primary-500">
              <option value="all">Semua Emosi</option>
              <option value="high">Intensitas Tinggi (4)</option>
              <option value="custom">Emosi Kustom</option>
            </select>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-100">
            <thead>
              <tr class="bg-gray-50">
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Siswa</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Emosi</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Intensitas</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-if="isLoading">
                <td colspan="5" class="px-4 py-12 text-center">
                  <div class="flex items-center justify-center gap-2 text-primary-600">
                    <Icon name="svg-spinners:180-ring-with-bg" class="w-6 h-6" />
                    <span class="text-sm font-bold">Memuat data...</span>
                  </div>
                </td>
              </tr>
              <template v-else-if="moodStudentData.mood_logs.length > 0">
                <tr v-for="mood in moodStudentData.mood_logs" :key="mood.id" class="hover:bg-gray-50 transition">
                  <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                      <img :src="mood.student_avatar_url" class="w-8 h-8 rounded-full bg-gray-200" />
                      <div>
                        <div class="text-sm font-bold text-gray-900">{{ mood.student_name }}</div>
                        <div class="text-xs text-gray-500">{{ mood.student_classroom }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <span
                      :class="['px-2.5 py-1 rounded-full text-xs font-bold transition-colors', getEmotionClass(mood)]">
                      <span v-if="mood.is_custom" class="mr-1">✨</span>
                      {{ mood.emotion_name }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex items-center gap-1">
                      <div v-for="i in 4" :key="i" :class="[
                        'h-1.5 w-4 rounded-full transition-all duration-300',
                        i <= mood.magnitude
                          ? getMagnitudeColor(mood.magnitude, mood.emotion_name)
                          : 'bg-gray-200'
                      ]"></div>
                      <span class="text-xs ml-2 font-bold text-gray-600">{{ mood.magnitude }}/4</span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-xs text-gray-500 italic">
                    {{ mood.time_ago }}
                  </td>
                  <td class="px-4 py-4 text-right">
                    <NuxtLink :to="`/students/detail/${mood.student_id}`"
                      class="text-primary-600 hover:text-primary-800 text-sm font-bold">Detail</NuxtLink>
                  </td>
                </tr>
              </template>

              <tr v-else>
                <td colspan="5" class="px-4 py-12 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <Icon name="tabler:mood-empty" class="w-12 h-12 text-gray-300 mb-2" />
                    <p class="text-sm font-bold text-gray-500">Belum ada data emosi hari ini</p>
                    <p class="text-xs text-gray-400">Data akan muncul setelah siswa mengisi mood harian mereka.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-else class="col-span-12 lg:col-span-8 bg-white p-10 text-center rounded-xl">
      <Icon name="tabler:lock" class="w-12 h-12 text-gray-300 mx-auto mb-4" />
      <p class="text-gray-500">Detail emosi siswa hanya dapat diakses oleh Guru BK dan Guru Kelas.</p>
    </div>

    <div class="col-span-12 lg:col-span-4 space-y-6">
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
          <Icon name="tabler:urgent" class="text-red-500" />
          Butuh Perhatian Segera
        </h3>

        <div v-if="moodStudentData.vulnerable_students?.length > 0" class="space-y-4">
          <div v-for="student in moodStudentData.vulnerable_students" :key="student.id"
            class="p-4 border border-red-100 bg-red-50 rounded-xl">
            <div class="flex justify-between items-start mb-2">
              <span class="text-sm font-bold text-gray-900">{{ student.student_name }}</span>
              <span class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full uppercase">Kritis</span>
            </div>
            <p class="text-xs text-gray-600 line-clamp-2 italic">"{{ student.story }}"</p>
            <div class="mt-3 flex gap-2">
              <button v-if="can('bk')"
                class="flex-1 bg-white border border-red-200 text-red-600 py-1.5 rounded-lg text-xs font-bold hover:bg-red-100 transition">
                Hubungi Siswa
              </button>
              <p v-else class="text-[10px] text-red-400 italic">BK akan segera menindaklanjuti</p>
            </div>
          </div>
        </div>

        <div v-else class="py-8 flex flex-col items-center justify-center text-center">
          <div class="p-3 bg-green-50 rounded-full text-green-500 mb-3 flex items-center">
            <Icon name="tabler:circle-check" class="w-10 h-10" />
          </div>
          <p class="text-sm font-bold text-gray-800">Kabar Baik!</p>
          <p class="text-xs text-gray-500 max-w-[200px] mt-1">
            Tidak ada siswa dengan tingkat emosi kritis yang terdeteksi hari ini.
          </p>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
        <p class="text-sm text-gray-500 mb-2 font-medium">Partisipasi Hari Ini</p>

        <div class="text-3xl font-black text-primary-600">
          {{ moodStudentData.stats?.participation_percentage || 0 }}%
        </div>

        <div class="w-full bg-gray-100 h-2 rounded-full mt-3 overflow-hidden">
          <div class="bg-primary-500 h-2 rounded-full transition-all duration-500"
            :style="{ width: `${moodStudentData.stats?.participation_percentage || 0}%` }"></div>
        </div>

        <p class="text-[10px] text-gray-400 mt-2">
          {{ moodStudentData.stats?.filled_today || 0 }} dari {{ moodStudentData.stats?.total_students || 0 }} siswa
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { can } = usePermission();
const moodStudentData = ref<MoodData>({
  stats: {
    total_students: 0,
    filled_today: 0,
    participation_percentage: 0,
    avg_stress_level: 0,
    vulnerable_count: 0
  },
  mood_logs: [],
  vulnerable_students: []
} as MoodData);
const isLoading = ref<boolean>(true);
const filterStatus = ref('all');

// Kelompokkan emosi
const negativeEmotions = ['Sedih', 'Marah', 'Takut'];
const positiveEmotions = ['Senang', 'Bangga', 'Biasa Aja'];

// Fungsi untuk menentukan tema warna badge emosi
function getEmotionClass(mood: any) {
  if (mood.is_custom) {
    return 'bg-purple-100 text-purple-700 border border-purple-200';
  }

  if (negativeEmotions.includes(mood.emotion_name)) {
    return 'bg-red-100 text-red-700 border border-red-200';
  }

  if (positiveEmotions.includes(mood.emotion_name)) {
    return 'bg-emerald-100 text-emerald-700 border border-emerald-200';
  }

  return 'bg-gray-100 text-gray-700';
}

// Fungsi warna bar intensitas yang lebih cerdas
function getMagnitudeColor(magnitude: number, emotionName: string) {
  const isNegative = negativeEmotions.includes(emotionName);

  if (isNegative) {
    if (magnitude >= 4) return 'bg-red-600';
    if (magnitude >= 3) return 'bg-orange-500';
    return 'bg-amber-400';
  } else {
    // Jika emosi positif, gunakan gradasi hijau/biru
    if (magnitude >= 4) return 'bg-emerald-600';
    return 'bg-emerald-400';
  }
}

async function getMoodData() {
  isLoading.value = true;
  const resArticle = await useApi().get<MoodData>('/moods');

  if (resArticle.status && resArticle.data) {
    moodStudentData.value = resArticle.data;
  }
  isLoading.value = false;
}

onMounted(() => {
  getMoodData();
})
</script>