<template>
  <div class="space-y-6 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-gray-900">Halo, Admin BK 👋</h1>
        <p class="text-sm text-gray-500 font-medium">Berikut adalah rangkuman kondisi mental sekolah hari ini.</p>
      </div>
      <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100">
        <button class="px-4 py-2 text-xs font-bold bg-primary-600 text-white rounded-xl shadow-md">Overview</button>
        <button class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-50 rounded-xl">Laporan Tahunan</button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="stat in summaryStats" :key="stat.label" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center justify-between mb-4">
          <div :class="['p-3 rounded-2xl transition-colors flex items-center', stat.bgIcon]">
            <Icon :name="stat.icon" :class="['w-6 h-6', stat.textIcon]" />
          </div>
          <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ stat.trend }}</span>
        </div>
        <h3 class="text-2xl font-black text-gray-900">{{ stat.value }}</h3>
        <p class="text-xs font-bold text-gray-400 uppercase mt-1">{{ stat.label }}</p>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
      <div class="col-span-12 lg:col-span-8 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-lg font-black text-gray-900">Indeks Kebahagiaan Sekolah</h3>
            <p class="text-xs text-gray-500 font-medium">Data diakumulasi dari seluruh input harian siswa</p>
          </div>
          <select class="bg-gray-50 border-none text-xs font-bold rounded-xl px-4 py-2 outline-none ring-1 ring-gray-200">
            <option>7 Hari Terakhir</option>
            <option>30 Hari Terakhir</option>
          </select>
        </div>
        <div class="h-[300px]">
          <Line :data="chartData" :options="chartOptions" />
        </div>
      </div>

      <div class="col-span-12 lg:col-span-4 space-y-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
          <h3 class="text-sm font-black text-gray-900 mb-6 flex items-center gap-2">
            <Icon name="tabler:alert-triangle" class="text-red-500" />
            Intervensi Mendesak
          </h3>
          <div class="space-y-4">
            <div v-for="student in urgentStudents" :key="student.name" class="p-4 bg-red-50 rounded-2xl border border-red-100 group hover:bg-red-100 transition-colors">
              <div class="flex justify-between items-start">
                <div>
                  <p class="text-sm font-black text-gray-900">{{ student.name }}</p>
                  <p class="text-[10px] text-red-700 font-bold uppercase tracking-tighter">{{ student.reason }}</p>
                </div>
                <NuxtLink :to="`/students/detail/${student.id}`" class="p-2 bg-white rounded-xl shadow-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center">
                  <Icon name="tabler:chevron-right" class="text-red-600" />
                </NuxtLink>
              </div>
            </div>
          </div>
          <button class="w-full mt-6 py-3 text-xs font-black text-primary-600 border-2 border-primary-50 rounded-2xl hover:bg-primary-50 transition-all">
            LIHAT SEMUA DATA RENTAN
          </button>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <h3 class="text-sm font-black text-gray-900 mb-6">Status Konseling</h3>
        <div class="space-y-4">
          <div v-for="status in counselingStatus" :key="status.label" class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
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
                <th class="pb-4"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="req in recentRequests" :key="req.id" class="group">
                <td class="py-4">
                  <p class="text-sm font-bold text-gray-900">{{ req.student }}</p>
                  <p class="text-[10px] text-gray-400">{{ req.class }}</p>
                </td>
                <td class="py-4 text-xs font-medium text-gray-600">{{ req.title }}</td>
                <td class="py-4">
                  <span :class="['px-2 py-1 rounded-lg text-[10px] font-black uppercase', req.urgency === 'high' ? 'bg-red-100 text-red-700' : 'bg-primary-100 text-primary-700']">
                    {{ req.urgency }}
                  </span>
                </td>
                <td class="py-4 text-right">
                  <NuxtLink :to="`/counseling/detail/${req.id}`" class="text-xs font-black text-primary-600 hover:underline">Detail →</NuxtLink>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
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
  CategoryScale, // <--- Ini yang menyebabkan error "category"
  LinearScale,   // <--- Ini untuk sumbu Y (angka)
  Filler 
} from 'chart.js'

// Daftarkan semua komponen yang dibutuhkan
ChartJS.register(
  Title, 
  Tooltip, 
  Legend, 
  LineElement, 
  PointElement, 
  CategoryScale, 
  LinearScale, 
  Filler
)

const summaryStats = [
  { label: 'Total Siswa', value: '1,240', icon: 'tabler:users', bgIcon: 'bg-primary-50', textIcon: 'text-primary-600', trend: '↑ 12%' },
  { label: 'Mood Terisi', value: '856', icon: 'tabler:mood-smile', bgIcon: 'bg-emerald-50', textIcon: 'text-emerald-600', trend: '↑ 5%' },
  { label: 'Kasus Aktif', value: '24', icon: 'tabler:message-report', bgIcon: 'bg-amber-50', textIcon: 'text-amber-600', trend: '↓ 2%' },
  { label: 'Siswa Rentan', value: '7', icon: 'tabler:alert-octagon', bgIcon: 'bg-red-50', textIcon: 'text-red-600', trend: 'STABIL' },
];

const urgentStudents = [
  { id: '1', name: 'Andi Hermawan', reason: 'Emosi Negatif 3 Hari Berturut-turut' },
  { id: '2', name: 'Siti Aminah', reason: 'Magnitude Stress Level 4 (Kritis)' },
];

const counselingStatus = [
  { label: 'Selesai', count: 142, color: 'bg-emerald-500' },
  { label: 'Dalam Proses', count: 18, color: 'bg-primary-500' },
  { label: 'Menunggu', count: 6, color: 'bg-amber-500' },
];

const recentRequests = [
  { id: 'req1', student: 'Budi Santoso', class: 'XII-RPL 1', title: 'Kesulitan Belajar', urgency: 'medium' },
  { id: 'req2', student: 'Rina Rose', class: 'XI-TKJ 2', title: 'Masalah Keluarga', urgency: 'high' },
];

// Chart Data & Options...
const chartData = {
  labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
  datasets: [{
    label: 'Happiness Index',
    data: [65, 59, 80, 81, 56, 55, 40],
    borderColor: '#4f46e5',
    backgroundColor: 'rgba(79, 70, 229, 0.1)',
    fill: true,
    tension: 0.4
  }]
};
const chartOptions = { responsive: true, maintainAspectRatio: false };
</script>