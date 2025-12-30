<template>
  <div class="space-y-6 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-gray-900">Halo, {{ auth.user?.name }}</h1>
        <p class="text-sm text-gray-500 font-medium">Berikut adalah rangkuman kondisi mental siswa di sekolah hari ini.
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="stat in summaryStats" :key="stat.label"
        class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between mb-4">
          <div :class="['p-3 rounded-2xl transition-colors flex items-center', stat.bgIcon]">
            <Icon :name="stat.icon" :class="['w-6 h-6', stat.textIcon]" />
          </div>
          <!-- <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ stat.trend }}</span> -->
        </div>
        <h3 class="text-2xl font-black text-gray-900">{{ stat.value }}</h3>
        <p class="text-xs font-bold text-gray-400 uppercase mt-1">{{ stat.label }}</p>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
      <div v-if="can(['bk', 'guru'])"
        class="col-span-12 lg:col-span-8 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-lg font-black text-gray-900">Indeks Kebahagiaan Sekolah</h3>
            <p class="text-xs text-gray-500 font-medium">Data diakumulasi dari seluruh input harian siswa</p>
          </div>
          <select
            class="bg-gray-50 border-none text-xs font-bold rounded-xl px-4 py-2 outline-none ring-1 ring-gray-200">
            <option>7 Hari Terakhir</option>
            <option>30 Hari Terakhir</option>
          </select>
        </div>
        <div class="h-[300px]">
          <Line :data="chartData" :options="chartOptions" />
        </div>
      </div>

      <div class="col-span-12 lg:col-span-4 space-y-6">
        <div v-if="can(['bk', 'guru'])" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
          <h3 class="text-sm font-black text-gray-900 mb-6 flex items-center gap-2">
            <Icon name="tabler:alert-triangle" class="text-red-500" />
            Intervensi Mendesak
          </h3>
          <div class="space-y-4">
            <div v-for="student in overview?.urgent_interventions" :key="student.name"
              class="p-4 bg-red-50 rounded-2xl border border-red-100 group hover:bg-red-100 transition-colors">
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-sm font-black text-gray-900">{{ student.name }}</p>
                  <p class="text-[10px] text-red-700 font-bold uppercase tracking-tighter">{{ student.reason }}</p>
                </div>
                <NuxtLink :to="`/students/detail/${student.id}`"
                  class="p-2 bg-white rounded-xl shadow-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center">
                  <Icon name="tabler:chevron-right" class="text-red-600" />
                </NuxtLink>
              </div>
            </div>
          </div>
          <button
            class="w-full mt-6 py-3 text-xs font-black text-primary-600 border-2 border-primary-50 rounded-2xl hover:bg-primary-50 transition-all">
            LIHAT SEMUA DATA RENTAN
          </button>
        </div>
      </div>

      <template v-if="can('bk')">
        <div class="col-span-12 lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
          <h3 class="text-sm font-black text-gray-900 mb-6">Status Konseling</h3>
          <div class="space-y-4">
            <div v-for="status in counselingStatus" :key="status.label"
              class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
              <div class="flex items-center gap-3">
                <div :class="['w-2 h-2 rounded-full', status.color]"></div>
                <span class="text-xs font-bold text-gray-600">{{ status.label }}</span>
              </div>
              <span class="text-xs font-black text-gray-900">{{ status.count }}</span>
            </div>
          </div>
        </div>

        <div class="col-span-12 lg:col-span-8 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
          <h3 class="text-sm font-black text-gray-900 mb-6">Permintaan Konseling Terbaru</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left border-b border-gray-50">
                  <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Siswa</th>
                  <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Topik</th>
                  <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Urgency</th>
                  <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                  <th class="pb-4"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="req in overview?.recent_requests" :key="req.id" class="group">
                  <td class="py-4">
                    <p class="text-sm font-bold text-gray-900">{{ req.student }}</p>
                    <p class="text-[10px] text-gray-400">{{ req.class }}</p>
                  </td>
                  <td class="py-4 text-xs font-medium text-gray-600">{{ req.title }}</td>
                  <td class="py-4">
                    <span
                      :class="['px-2 py-1 rounded-lg text-[10px] font-black uppercase', req.urgency === 'high' ? 'bg-red-100 text-red-700' : 'bg-primary-100 text-primary-700']">
                      {{ req.urgency }}
                    </span>
                  </td>
                  <td class="py-4">
                    <span
                      :class="['px-2 py-1 rounded-lg text-[10px] font-black uppercase', req.status === 'scheduled' ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700']">
                      {{ req.status }}
                    </span>
                  </td>
                  <td class="py-4 text-right">
                    <NuxtLink :to="`/counseling/${req.id}`" class="text-xs font-black text-primary-600 hover:underline">
                      Detail →</NuxtLink>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>

    </div>
  </div>
</template>

<script setup lang="ts">
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Filler,
  scales
} from 'chart.js';

const { can } = usePermission();

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Filler
);

const auth = useAuthStore();

const { data: overview, pending, error, refresh } = await useAsyncData(
  `overview`,
  async () => {
    try {
      const res = await useApi().get<DashboardOverview>('/dashboard-overview');

      if (!res.status || !res.data) {
        throw createError({ statusCode: 404, statusMessage: 'Data tidak ditemukan' });
      }

      return res.data;
    } catch (e: any) {
      throw e;
    }
  }
);

const summaryStats = computed(() => [
  {
    label: 'Total Siswa',
    value: overview.value?.summary_stats.total_students.value.toLocaleString(),
    icon: 'tabler:users',
    bgIcon: 'bg-primary-50',
    textIcon: 'text-primary-600',
    trend: `↑ ${overview.value?.summary_stats.total_students.percentage}%`
  },
  {
    label: 'Mood Terisi',
    value: overview.value?.summary_stats.mood_entries.value.toLocaleString(),
    icon: 'tabler:mood-smile',
    bgIcon: 'bg-emerald-50',
    textIcon: 'text-emerald-600',
    trend: `↑ ${overview.value?.summary_stats.mood_entries.percentage}%`
  },
  {
    label: 'Kasus Aktif',
    value: overview.value?.summary_stats.active_cases.value.toLocaleString(),
    icon: 'tabler:message-report',
    bgIcon: 'bg-amber-50',
    textIcon: 'text-amber-600',
    trend: `↓ ${overview.value?.summary_stats.active_cases.percentage}%`
  },
  {
    label: 'Siswa Rentan',
    value: overview.value?.summary_stats.vulnerable_students.value.toLocaleString(),
    icon: 'tabler:alert-octagon',
    bgIcon: 'bg-red-50',
    textIcon: 'text-red-600',
    trend: 'STABIL'
  },
]);

const chartData = computed(() => ({
  labels: overview.value?.happiness_index.labels || [],
  datasets: [{
    label: 'Happiness Index',
    data: overview.value?.happiness_index.values || [],
    borderColor: '#4f46e5',
    backgroundColor: 'rgba(79, 70, 229, 0.1)',
    fill: true,
    tension: 0.4
  }]
}));

const counselingStatus = computed(() => [
  { label: 'Selesai', count: overview.value?.counseling_status.completed, color: 'bg-emerald-500' },
  { label: 'Dalam Proses', count: overview.value?.counseling_status.in_progress, color: 'bg-primary-500' },
  { label: 'Menunggu', count: overview.value?.counseling_status.pending, color: 'bg-amber-500' },
]);

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      max: 100
    }
  }
};
</script>