<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div class="flex justify-between items-end pb-3 border-b border-gray-100 gap-4">
      <div class="flex items-baseline gap-2">
        <h1 class="text-2xl font-bold text-gray-900 leading-none">Notifikasi</h1>
      </div>

      <button @click="markAllAsRead" :disabled="unreadCount === 0"
        class="text-xs sm:text-sm font-bold transition-all duration-200 pb-0.5 leading-none group"
        :class="[unreadCount > 0 ? 'text-primary-600 hover:text-primary-700' : 'text-gray-400 cursor-default']">
        <span class="flex items-center gap-1.5">
          <Icon :name="unreadCount > 0 ? 'tabler:checks' : 'tabler:check'"
            class="w-4 h-4 transition-transform group-hover:scale-110" />
          <span class="hidden sm:inline">Tandai Semua Sudah Dibaca</span>
          <span class="inline sm:hidden">Baca Semua</span>
        </span>
      </button>
    </div>

    <div class="space-y-2">
      <div v-for="notification in notifications" :key="notification.id" @click="handleNotificationClick(notification)"
        :class="[
          'flex items-start gap-4 p-3 rounded-xl transition duration-150 cursor-pointer border',
          notification.isRead
            ? 'bg-white hover:bg-gray-50 border-gray-100'
            : 'bg-primary-50 border-primary-200 hover:bg-primary-100'
        ]">
        <div class="shrink-0 pt-1">
          <div :class="[
            'p-2 rounded-lg',
            notification.isRead ? 'bg-gray-100' : 'bg-primary-100'
          ]">
            <Icon :name="notification.icon" :class="[
              'w-6 h-6 flex items-center',
              notification.isRead ? 'text-gray-500' : 'text-primary-700'
            ]" />
          </div>
        </div>

        <div class="grow space-y-1">
          <div class="flex justify-between items-start gap-2">
            <h3 :class="[
              'text-sm sm:text-base',
              notification.isRead ? 'text-gray-700 font-semibold' : 'text-gray-900 font-extrabold'
            ]">
              {{ notification.title }}
            </h3>

            <div v-if="!notification.isRead" class="shrink-0 pt-1.5">
              <span class="block w-2 h-2 bg-primary-600 rounded-full"></span>
            </div>
          </div>

          <p :class="[
            'text-xs sm:text-sm leading-relaxed',
            notification.isRead ? 'text-gray-500' : 'text-gray-700 font-medium'
          ]">
            {{ notification.message }}
          </p>

          <p :class="[
            'text-[10px] sm:text-xs mt-2',
            notification.isRead ? 'text-gray-400' : 'text-primary-600 font-bold'
          ]">
            {{ formatTimeAgo(notification.timestamp) }}
          </p>
        </div>
      </div>
    </div>

    <div v-if="notifications.length === 0" class="text-center py-10">
      <Icon name="tabler:mood-happy" class="w-12 h-12 text-gray-400 mx-auto" />
      <p class="mt-4 text-gray-600">Tidak ada notifikasi baru hari ini.</p>
    </div>

  </div>
</template>

<script setup lang="ts">
const notifications = ref<Notification[]>([]);
const isLoading = ref(true);

// Fungsi untuk mengambil data dari API
async function fetchNotifications() {
  isLoading.value = true;
  try {
    const res = await useApi().get<Notification[]>('/student/notifications');

    if (res.status) {
      notifications.value = res.data;
    }
  } catch (error) {
    useToast().error("Gagal mengambil notifikasi");
  } finally {
    isLoading.value = false;
  }
}

const handleNotificationClick = async (notification: Notification) => {
  if (!notification.isRead) {
    await markAsRead(notification.id)
    notification.isRead = true
  }

  if (notification.link) {
    navigateTo(notification.link)
  }
}

// Aksi Tandai Satu Sudah Dibaca
async function markAsRead(id: string) {
  const notification = notifications.value.find(n => n.id === id);
  if (notification && !notification.isRead) {
    try {
      await useApi().post(`/student/notifications/${id}/mark-as-read`);
      notification.isRead = true;
    } catch (err) {
      console.error(err);
    }
  }
}

// Aksi Tandai Semua
async function markAllAsRead() {
  try {
    await useApi().post('/student/notifications/mark-all-read');
    notifications.value.forEach(n => n.isRead = true);
    useToast().success("Semua pesan telah dibaca");
  } catch (err) {
    useToast().error("Gagal memperbarui status");
  }
}

onMounted(() => {
  fetchNotifications();
});

// Computed & formatTimeAgo tetap sama seperti kode Anda sebelumnya
const unreadCount = computed(() => notifications.value.filter(n => !n.isRead).length);
</script>