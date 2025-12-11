<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div class="flex items-center space-x-4 pb-2 border-b border-gray-100">
      <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
    </div>

    <div class="flex justify-between items-center px-0">
      <p class="text-lg font-bold text-gray-800">Semua Notifikasi</p>

      <button @click="markAllAsRead" :disabled="unreadCount === 0"
        class="text-xs sm:text-sm font-medium transition duration-200 whitespace-nowrap"
        :class="[unreadCount > 0 ? 'text-primary-600 hover:text-primary-700' : 'text-gray-400 cursor-default']">
        <span class="hidden sm:inline">Tandai Semua Sudah Dibaca</span>
        <span class="inline sm:hidden">Baca Semua</span>
        ({{ unreadCount }})
      </button>
    </div>

    <div class="space-y-2">
      <div v-for="notification in notifications" :key="notification.id">
        <NuxtLink :to="notification.link" :class="[
          'flex items-start gap-4 p-3 rounded-xl transition duration-150',
          // Styling untuk notifikasi belum dibaca
          notification.isRead
            ? 'bg-white hover:bg-gray-50 border border-transparent'
            : 'bg-primary-50 border border-primary-200 hover:bg-primary-100'
        ]" @click="markAsRead(notification.id)">

          <div class="shrink-0 pt-1">
            <Icon :name="notification.icon"
              :class="['w-6 h-6', notification.isRead ? 'text-primary-500' : 'text-primary-700']" />
          </div>

          <div class="grow">
            <p :class="['text-sm', notification.isRead ? 'text-gray-700 font-medium' : 'text-gray-900 font-bold']">
              {{ notification.message }}
            </p>
            <p :class="['text-xs mt-1', notification.isRead ? 'text-gray-500' : 'text-primary-600 font-semibold']">
              {{ formatTimeAgo(notification.timestamp) }}
            </p>
          </div>

          <div v-if="!notification.isRead" class="shrink-0 pt-2">
            <span class="block w-2 h-2 bg-primary-600 rounded-full"></span>
          </div>

        </NuxtLink>
      </div>
    </div>

    <div v-if="notifications.length === 0" class="text-center py-10">
      <Icon name="tabler:mood-happy" class="w-12 h-12 text-gray-400 mx-auto" />
      <p class="mt-4 text-gray-600">Tidak ada notifikasi baru hari ini.</p>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// 1. Data Dummy Notifikasi
const notifications = ref([
  {
    id: 1,
    message: "Guru BK Bpk. Dwi Prasetyo telah membalas pesan konseling Anda.",
    icon: "tabler:message-2-code",
    link: "/chat/1",
    timestamp: Date.now() - 30 * 60 * 1000, // 30 menit lalu
    isRead: false,
  },
  {
    id: 2,
    message: "Artikel baru: 'Tips Atasi Prokrastinasi Sebelum Ujian' sudah terbit.",
    icon: "tabler:book-2",
    link: "/articles/prokrastinasi-ujian",
    timestamp: Date.now() - 2 * 60 * 60 * 1000, // 2 jam lalu
    isRead: false,
  },
  {
    id: 3,
    message: "Selamat! Poin aktivitas Anda bulan ini bertambah 10 poin.",
    icon: "tabler:award",
    link: "/profile/points",
    timestamp: Date.now() - 1 * 24 * 60 * 60 * 1000, // 1 hari lalu
    isRead: true,
  },
  {
    id: 4,
    message: "Jadwal konseling dengan Ibu Rina pada hari Kamis, 15:00, telah dikonfirmasi.",
    icon: "tabler:calendar-check",
    link: "/calendar/event-5",
    timestamp: Date.now() - 3 * 24 * 60 * 60 * 1000, // 3 hari lalu
    isRead: true,
  },
]);

// 2. Computed untuk Menghitung Notifikasi Belum Dibaca
const unreadCount = computed(() => {
  return notifications.value.filter(n => !n.isRead).length;
});

// 3. Fungsi Aksi
function markAsRead(id) {
  const index = notifications.value.findIndex(n => n.id === id);
  if (index !== -1) {
    // Tidak perlu melakukan apa-apa jika sudah dibaca
    if (!notifications.value[index].isRead) {
      notifications.value[index].isRead = true;
    }
  }
}

function markAllAsRead() {
  notifications.value.forEach(n => {
    n.isRead = true;
  });
}

// 4. Fungsi Format Waktu (Contoh Sederhana)
function formatTimeAgo(timestamp) {
  const now = Date.now();
  const diff = now - timestamp;
  const minutes = Math.floor(diff / (1000 * 60));
  const hours = Math.floor(diff / (1000 * 60 * 60));
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));

  if (minutes < 1) return 'Baru saja';
  if (minutes < 60) return `${minutes} menit lalu`;
  if (hours < 24) return `${hours} jam lalu`;
  return `${days} hari lalu`;
}

// Opsional: Urutkan notifikasi dari yang terbaru ke terlama saat dimuat
notifications.value.sort((a, b) => b.timestamp - a.timestamp);
</script>