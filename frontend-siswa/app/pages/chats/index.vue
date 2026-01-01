<template>
  <div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <AppHeader title="Daftar Konseling Saya" icon="tk:chat-bold" class="mb-0" />

      <NuxtLink to="/chats/new-request" v-if="counselingList.length > 0"
        class="flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition duration-200 shadow-md shadow-primary-200">
        <Icon name="heroicons:plus-circle-solid" class="w-5 h-5" />
        Tambah Konseling
      </NuxtLink>
    </div>

    <div class="space-y-4">
      <div v-if="isLoading" class="flex flex-col items-center justify-center py-20 animate-fade-in">
        <div class="relative flex items-center justify-center">
          <div class="absolute inset-0 bg-primary-200 rounded-full blur-xl opacity-40 animate-pulse"></div>

          <Icon name="svg-spinners:ring-resize" class="w-12 h-12 text-primary-600 relative z-10" />
        </div>

        <div class="mt-4 text-center">
          <p class="text-lg font-bold text-gray-800">Mohon Tunggu</p>
          <p class="text-sm text-gray-500">Sedang memuat riwayat konseling kamu...</p>
        </div>
      </div>

      <div v-else-if="counselingList.length > 0" class="space-y-4">
        <div v-for="item in counselingList" :key="item.id"
          class="bg-white p-5 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-200">

          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="grow">
              <div class="flex items-center gap-2 mb-1">
                <h3 class="text-lg font-bold text-gray-900">{{ item.title }}</h3>
                <span :class="[
                  'text-[10px] px-2 py-0.5 rounded-full font-bold uppercase',
                  item.urgency === 'high' ? 'bg-red-100 text-red-600' : 'bg-primary-100 text-primary-600'
                ]">
                  {{ item.urgency }}
                </span>
              </div>

              <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ item.description }}</p>

              <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2">
                <div class="flex items-center">
                  <span :class="[
                    'w-2.5 h-2.5 rounded-full mr-2',
                    !item.schedule ? 'bg-amber-500' :
                      (item.schedule.status === 'pending' ? 'bg-primary-500' : 'bg-green-500')
                  ]"></span>

                  <p class="text-xs font-semibold text-gray-500">
                    <template v-if="!item.schedule">
                      Menunggu Penjadwalan
                    </template>
                    <template v-else-if="item.schedule.status === 'pending'">
                      Menunggu Persetujuan
                    </template>
                    <template v-else-if="item.schedule.status === 'confirmed'">
                      Jadwal Terkonfirmasi
                    </template>
                    <template v-else>
                      {{ item.schedule.status }}
                    </template>
                  </p>
                </div>
                <p class="text-xs text-gray-400">
                  Dibuat: {{ new Date(item.created_at).toLocaleDateString('id-ID') }}
                </p>
              </div>
            </div>

            <div class="shrink-0">
              <button @click="handleAction(item)" :disabled="getActionButton(item).disabled" :class="[
                'w-full md:w-auto py-2.5 px-6 rounded-lg font-bold text-sm transition duration-150 flex items-center justify-center gap-2',
                getActionButton(item).class
              ]">
                <Icon :name="getActionButton(item).icon" class="w-4 h-4" />
                <span>{{ getActionButton(item).text }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12 px-6 bg-white rounded-2xl border-gray-200">
        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <Icon name="heroicons:chat-bubble-bottom-center-text" class="w-8 h-8 text-gray-400" />
        </div>
        <p class="text-lg font-semibold text-gray-700">Belum ada riwayat konseling</p>
        <p class="mt-2 text-gray-500 text-sm">Ceritakan apa yang kamu rasakan kepada Guru BK kami.</p>

        <div class="mt-6">
          <NuxtLink to="/chats/new-request"
            class="inline-block py-3 px-8 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 transition duration-150 shadow-lg shadow-primary-200">
            Mulai Konseling Baru
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const counselingList = ref([]);
const isLoading = ref(true);

const getActionButton = (item) => {
  const status = item.schedule?.status || 'none';
  const type = item.schedule.method;

  const configs = {
    none: {
      text: 'Pilih Jadwal',
      icon: 'heroicons:calendar-days-solid',
      class: 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm',
      disabled: false,
    },
    pending: {
      text: 'Menunggu Persetujuan',
      icon: 'heroicons:clock-solid',
      class: 'bg-gray-200 text-gray-400 cursor-not-allowed',
      disabled: true,
    },
    confirmed: {
      // LOGIKA DINAMIS DI SINI
      text: type === 'chat' ? 'Mulai Chat' : 'Detail Pertemuan',
      icon: type === 'chat' ? 'heroicons:chat-bubble-left-right-solid' : 'heroicons:map-pin-solid',
      class: type === 'chat'
        ? 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm'
        : 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm',
      disabled: false,
    },
    completed: {
      text: 'Selesai',
      icon: 'heroicons:check-circle-solid',
      class: 'bg-slate-200 text-slate-600 cursor-default',
      disabled: true,
    },
    canceled: {
      text: 'Ditolak',
      icon: 'heroicons:x-circle-solid',
      class: 'bg-red-100 text-red-600 cursor-default',
      disabled: true,
    },
  };

  return configs[status] || configs.none;
};

const fetchData = async () => {
  isLoading.value = true;
  try {
    const res = await useApi().get('/student/counseling');
    // Sesuai contoh JSON anda: res.data adalah array
    if (res && res.data) {
      counselingList.value = res.data;
    }
  } catch (error) {
    console.error('Error fetching counseling list:', error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchData();
});

function handleAction(item) {
  if (!item.schedule) {
    return navigateTo({
      path: '/chats/counselors',
      query: { requestId: item.id }
    });
  }

  // 2. Jika sudah pilih jadwal tapi Guru BK belum menyetujui (Status Pending)
  if (item.schedule.status === 'pending') {
    useToast().error("Jadwal Anda sedang menunggu persetujuan Guru BK. Mohon cek kembali nanti.");
    return;
  }

  // 3. Jika sudah dikonfirmasi (Confirmed), baru boleh masuk ke chat
  if (item.schedule.status === 'confirmed') {
    const counselingType = item.schedule.method;

    if (counselingType === 'chat') {
      return navigateTo({
        path: `/chats/with/${item.id}`,
      });
    } else if (counselingType === 'face-to-face' || counselingType === 'f2f') {
      return navigateTo({
        path: `/chats/${item.id}`,
      });
    } else {
      useToast().error("Tipe konseling tidak dikenali.");
    }
  }
}
</script>