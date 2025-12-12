<template>
  <div class="space-y-6">

    <div class="flex items-center">
      <Icon name="tabler:school" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Edit Siswa: {{ form.name || 'Memuat...' }}
      </h1>
    </div>

    <div v-if="isLoading" class="p-8 text-center text-gray-500 bg-white rounded-xl shadow-lg border border-gray-200">
      <Icon name="tabler:loader-2" class="w-8 h-8 animate-spin mx-auto text-primary-600" />
      <p class="mt-2">Memuat data siswa...</p>
    </div>

    <div v-else class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

      <form @submit.prevent="submitStudentUpdate" class="space-y-6">

        <section class="space-y-4 border-b pb-4">
          <h2 class="text-xl font-semibold text-gray-800">Detail Identitas & Akun</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="nis" class="form-label">NIS <span class="text-red-600">*</span></label>
              <input type="text" id="nis" v-model="form.nis" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.nis }" />
              <p v-if="errors.nis" class="mt-1 text-xs text-red-500">{{ errors.nis[0] }}</p>
            </div>

            <div>
              <label for="nisn" class="form-label">NISN <span class="text-red-600">*</span></label>
              <input type="text" id="nisn" v-model="form.nisn" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.nisn }" />
              <p v-if="errors.nisn" class="mt-1 text-xs text-red-500">{{ errors.nisn[0] }}</p>
            </div>
          </div>

          <div>
            <label for="name" class="form-label">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" id="name" v-model="form.name" class="form-input" required :disabled="isSubmitting"
              :class="{ 'border-red-500': errors.name }" />
            <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="email" class="form-label">Email (Akun Login) <span class="text-red-600">*</span></label>
              <input type="email" id="email" v-model="form.email" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.email }" />
              <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
            </div>

            <div>
              <label for="password" class="form-label">Ganti Kata Sandi (Kosongkan jika tidak diubah)</label>
              <input type="password" id="password" v-model="form.password" class="form-input" :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.password }" />
              <p v-if="errors.password" class="mt-1 text-xs text-red-500">{{ errors.password[0] }}</p>
              <small class="text-gray-500 mt-1 block">Hanya isi jika Anda ingin mereset kata sandi.</small>
            </div>
          </div>
        </section>

        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-gray-800">Penugasan Kelas</h2>

          <FormSelect name="class_room_id" label="Pilih Kelas Siswa" v-model="form.class_room_id"
            placeholder="Pilih Kelas" :options="classRooms.map(c => ({ value: c.id, label: c.name }))" required
            :disabled="isSubmitting" :class="{ 'border-red-500': errors.class_room_id }" />
          <p v-if="errors.class_room_id" class="mt-1 text-xs text-red-500">{{ errors.class_room_id[0] }}</p>
        </section>

        <div class="pt-6 border-t flex justify-end space-x-3">
          <NuxtLink to="/students"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Batal
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting || isLoading"
            class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Perubahan Siswa
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const router = useRouter();

const studentId = route.params.id as string;
if (!studentId) {
  router.replace('/students');
}

interface StudentUpdatePayload {
  nis: string;
  nisn: string;
  name: string;
  email: string;
  password?: string;
  class_room_id: string;
}

interface MasterDataClassroom {
  id: string;
  name: string;
}

const initialForm: StudentUpdatePayload = {
  nis: '',
  nisn: '',
  name: '',
  email: '',
  password: undefined, // Kosongkan
  class_room_id: '',
};

const form = reactive<StudentUpdatePayload>({ ...initialForm });
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmitting = ref(false);
const isLoading = ref(true);

const classRooms = ref<MasterDataClassroom[]>([]);

async function fetchInitialData() {
  isLoading.value = true;
  let successCount = 0;

  try {
    const res = await useApi().get<MasterDataClassroom[]>('/master-data/classrooms');
    if (res.status && res.data) {
      classRooms.value = res.data;
      successCount++;
    } else {
      useToast().error('Gagal memuat daftar ruang kelas.');
    }
  } catch (e) {
    useToast().error('Error saat mengambil data kelas.');
  }

  try {
    const resStudent = await useApi().get<StudentUpdatePayload>(`/admin/students/${studentId}`);
    if (resStudent.status && resStudent.data) {
      const data = resStudent.data;

      Object.assign(form, {
        nis: data.nis,
        nisn: data.nisn,
        name: data.name,
        email: data.email,
        class_room_id: data.class_room_id,
        password: undefined,
      });
      successCount++;
    } else {
      useToast().error('Data siswa tidak ditemukan atau gagal dimuat.');
      router.replace('/admin/students');
    }

  } catch (e) {
    useToast().error('Terjadi kesalahan saat memuat data siswa.');
    router.replace('/students');
  } finally {
    isLoading.value = false;
    if (successCount < 2) {
      useToast().warning('Beberapa data mungkin tidak termuat dengan sempurna.');
    }
  }
}

const submitStudentUpdate = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  const payload: any = { ...form };
  if (!payload.password) {
    delete payload.password;
  }

  try {
    const response = await useApi().put(`/admin/students/${studentId}`, payload);

    if (response.status) {
      useToast().success('Data siswa berhasil diperbarui!');
      router.push('/students');
    } else {
      useToast().error(response.message || 'Gagal menyimpan perubahan.');
    }

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
      useToast().error('Validasi gagal. Silakan periksa kembali isian Anda.');
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan atau server.');
    }
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchInitialData();
});
</script>