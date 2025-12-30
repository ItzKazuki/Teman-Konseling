<template>
  <div class="space-y-8">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="space-y-2">
      <h1 class="text-2xl sm:text-3xl font-bold">Buat Jadwal Konseling</h1>
      <p class="text-gray-600 text-base">Anda akan membuat jadwal dengan:</p>
      <div class="inline-flex items-center space-x-2 p-2 bg-primary-100 rounded-lg">
        <Icon name="tabler:user-circle" class="w-5 h-5 text-primary-600" />
        <p class="font-semibold text-primary-800">{{ counselorName || 'Memuat...' }}</p>
      </div>
    </div>

    <form @submit.prevent="submitSchedule" class="space-y-6">

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">1. Pilih Metode Konseling</h2>
        <div class="grid grid-cols-2 gap-4">

          <div @click="form.method = 'chat'" :class="[
            'p-4 rounded-xl border-2 transition duration-200 cursor-pointer',
            form.method === 'chat'
              ? 'border-primary-600 bg-primary-50 shadow-md'
              : 'border-gray-200 hover:border-gray-300'
          ]">
            <Icon name="tabler:message-circle-2" class="w-6 h-6 mb-1"
              :class="form.method === 'chat' ? 'text-primary-600' : 'text-gray-500'" />
            <p class="font-semibold text-gray-900">Chat (WhatsApp)</p>
            <p class="text-xs text-gray-500">Nyaman, cepat, dan fleksibel.</p>
          </div>

          <div @click="form.method = 'face-to-face'" :class="[
            'p-4 rounded-xl border-2 transition duration-200 cursor-pointer',
            form.method === 'face-to-face'
              ? 'border-primary-600 bg-primary-50 shadow-md'
              : 'border-gray-200 hover:border-gray-300'
          ]">
            <Icon name="tabler:users" class="w-6 h-6 mb-1"
              :class="form.method === 'face-to-face' ? 'text-primary-600' : 'text-gray-500'" />
            <p class="font-semibold text-gray-900">Tatap Muka</p>
            <p class="text-xs text-gray-500">Konseling di ruang BK.</p>
          </div>
        </div>
      </div>

      <div class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800">2. Tanggal dan Waktu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="schedule-date" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
            <input id="schedule-date" type="date" v-model="form.date" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" />
          </div>
          <div>
            <label for="schedule-time" class="block text-sm font-medium text-gray-700 mb-1">
              Pilih Jam
            </label>
            <div class="relative">
              <select id="schedule-time" v-model="form.timeSlot" required
                :disabled="isLoadingSlots || availableTimeSlots.length === 0"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed">

                <option value="" disabled>
                  {{ isLoadingSlots ? 'Memuat jam...' : 'Pilih slot jam' }}
                </option>

                <option v-for="slot in availableTimeSlots" :key="slot" :value="slot">
                  {{ slot }}
                </option>
              </select>

              <div v-if="isLoadingSlots" class="absolute right-8 top-3.5">
                <Icon name="tabler:loader-2" class="w-5 h-5 animate-spin text-primary-600" />
              </div>
            </div>

            <p v-if="!isLoadingSlots && availableTimeSlots.length === 0 && form.date"
              class="text-xs text-rose-600 mt-2 flex items-center gap-1">
              <Icon name="tabler:alert-circle" class="w-4 h-4" />
              Jadwal penuh atau tidak tersedia pada tanggal ini.
            </p>
          </div>
        </div>
        <p v-if="form.method === 'face-to-face'" class="text-xs text-secondary-600 pt-1">
          *Pastikan waktu ini tidak bentrok dengan jam pelajaran Anda.
        </p>
      </div>

      <div class="pt-4">
        <button type="submit" :disabled="!isFormValid" :class="[
          'w-full py-4 rounded-xl font-semibold transition-colors duration-200 text-center',
          isFormValid
            ? 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400'
            : 'bg-gray-200 text-gray-500 cursor-not-allowed'
        ]">
          Ajukan Jadwal Konseling
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
const route = useRoute();

const requestId = route.query.requestId;
const counselorId = route.query.counselorId;

const form = reactive({
  counselorId: "", // Default/Hardcoded sesuai contoh curl atau ambil dari query
  method: 'chat', // Sesuaikan dengan enum API (huruf kecil)
  date: '',
  timeSlot: '',
  description: '', // Tetap ada di form untuk user, meski tidak dikirim di curl schedule
});

const counselorName = ref('Guru BK');
const isSubmitting = ref(false); // State untuk loading tombol

const availableTimeSlots = ref([]); // Sekarang jadi empty array
const isLoadingSlots = ref(false);

// Fungsi untuk mengambil jam tersedia
async function fetchAvailableSlots() {
  if (!form.date || !form.counselorId) return;

  isLoadingSlots.value = true;
  availableTimeSlots.value = []; // Reset jam lama
  form.timeSlot = ""; // Reset pilihan jam

  try {
    // Sesuaikan endpoint dengan API Laravel Anda
    // Contoh: /api/v1/counseling/available-slots?counselor_id=1&date=2023-10-25
    const res = await useApi().get('/student/counseling/available-slots', {
      params: {
        counselor_id: counselorId,
        date: form.date
      }
    });

    if (res.status) {
      availableTimeSlots.value = res.data; // Asumsi res.data adalah ['09:00', '10:00']
    }
  } catch (error) {
    console.error('Error fetch slots:', error);
    useToast().error('Gagal memuat jadwal tersedia');
  } finally {
    isLoadingSlots.value = false;
  }
}

const isFormValid = computed(() => {
  return (
    form.counselorId &&
    form.date !== '' &&
    form.timeSlot !== '' &&
    !isSubmitting.value
  );
});

async function submitSchedule() {
  if (!isFormValid.value) return;

  isSubmitting.value = true;

  // Sesuaikan format data dengan CURL yang diminta
  const payload = {
    counselor_id: form.counselorId,
    method: form.method, // Mapping ke format API
    schedule_date: `${form.date}T${form.timeSlot}:00Z`, // Gabungkan jadi ISO string sederhana
    time_slot: form.timeSlot
  };

  try {
    // URL menggunakan requestId yang didapat dari query param
    const res = await useApi().post(`/student/counseling/schedule/${requestId}`, payload);

    if (res.status) {
      useToast().success(`Jadwal berhasil diajukan!`);
      // Arahkan ke daftar utama (yang sudah Anda buat sebelumnya)
      // Di sana logic-nya otomatis akan berubah jadi "Mulai Chat" karena schedule sudah terisi
      navigateTo('/chats');
    } else {
      useToast().error('Gagal mengajukan jadwal: ' + (res.message || 'Terjadi kesalahan'));
    }
  } catch (error) {
    console.error('Error submitting schedule:', error);
    useToast().error('Terjadi kesalahan koneksi ke server.');
  } finally {
    isSubmitting.value = false;
  }
}

watch(() => form.date, () => {
  fetchAvailableSlots();
});

onMounted(async () => {
  const id = route.query.counselorId;
  const name = route.query.counselorName;

  if (id) form.counselorId = id;
  if (name) counselorName.value = name;

  form.date = new Date().toISOString().slice(0, 10);

  // Ambil slot untuk tanggal default hari ini
  await fetchAvailableSlots();
});
</script>