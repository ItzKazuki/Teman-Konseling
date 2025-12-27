<template>
  <div class="space-y-6">

    <div class="flex items-center">
      <Icon name="tabler:school" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Tambah Siswa Baru
      </h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

      <form @submit.prevent="submitStudent" class="space-y-6">

        <section class="space-y-4 border-b pb-6">
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
              <label for="phone_number" class="form-label">Nomor Telepon Siswa <span class="text-red-600">*</span></label>
              <input type="text" id="phone_number" v-model="form.phone_number" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.phone_number }" />
              <p v-if="errors.phone_number" class="mt-1 text-xs text-red-500">{{ errors.phone_number[0] }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             <div>
              <label for="password" class="form-label">Kata Sandi Awal <span class="text-red-600">*</span></label>
              <input type="password" id="password" v-model="form.password" class="form-input" required
                :disabled="isSubmitting" :class="{ 'border-red-500': errors.password }" />
              <p v-if="errors.password" class="mt-1 text-xs text-red-500">{{ errors.password[0] }}</p>
            </div>

            <FormSelect name="class_room_id" label="Penugasan Kelas" v-model="form.class_room_id"
              placeholder="Pilih Kelas" :options="classRooms.map(c => ({ value: c.id, label: c.name }))" required
              :disabled="isSubmitting" :class="{ 'border-red-500': errors.class_room_id }" />
          </div>
        </section>

        <section class="space-y-4 border-b pb-6">
          <h2 class="text-xl font-semibold text-gray-800">Alamat Tempat Tinggal</h2>
          
          <div>
            <label for="address" class="form-label">Alamat Lengkap</label>
            <textarea id="address" v-model="form.address" rows="3" class="form-input" :disabled="isSubmitting"></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label for="province" class="form-label">Provinsi</label>
              <input type="text" id="province" v-model="form.province" class="form-input" :disabled="isSubmitting" />
            </div>
            <div>
              <label for="city" class="form-label">Kota/Kabupaten</label>
              <input type="text" id="city" v-model="form.city" class="form-input" :disabled="isSubmitting" />
            </div>
            <div>
              <label for="district" class="form-label">Kecamatan</label>
              <input type="text" id="district" v-model="form.district" class="form-input" :disabled="isSubmitting" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="village" class="form-label">Desa/Kelurahan</label>
              <input type="text" id="village" v-model="form.village" class="form-input" :disabled="isSubmitting" />
            </div>
            <div>
              <label for="postal_code" class="form-label">Kode Pos</label>
              <input type="text" id="postal_code" v-model="form.postal_code" class="form-input" :disabled="isSubmitting" />
            </div>
          </div>
        </section>

        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-gray-800">Data Orang Tua / Wali</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="parent_name" class="form-label">Nama Orang Tua / Wali</label>
              <input type="text" id="parent_name" v-model="form.parent_name" class="form-input" :disabled="isSubmitting" />
            </div>
            <div>
              <label for="parent_phone_number" class="form-label">Nomor Telepon Orang Tua</label>
              <input type="text" id="parent_phone_number" v-model="form.parent_phone_number" class="form-input" :disabled="isSubmitting" />
            </div>
          </div>
        </section>

        <div class="pt-6 border-t flex justify-end space-x-3">
          <NuxtLink to="/students"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Batal
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting"
            class="flex items-center justify-center px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            <p>Daftarkan Siswa</p>
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup lang="ts">
const router = useRouter();

interface StudentCreationPayload {
  nis: string;
  nisn: string;
  name: string;
  email: string;
  password: string;
  class_room_id: string;
  phone_number: string;
  postal_code: string;
  address: string;
  village: string;
  district: string;
  city: string;
  province: string;
  parent_name: string;
  parent_phone_number: string;
}

const initialForm: StudentCreationPayload = {
  nis: '',
  nisn: '',
  name: '',
  email: '',
  password: '',
  class_room_id: '',
  phone_number: '',
  postal_code: '',
  address: '',
  village: '',
  district: '',
  city: '',
  province: '',
  parent_name: '',
  parent_phone_number: '',
};

const form = reactive<StudentCreationPayload>({ ...initialForm });
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmitting = ref(false);

const classRooms = ref<MasterDataClassroom[]>([]);

async function fetchClassRooms() {
  try {
    const res = await useApi().get<MasterDataClassroom[]>('/master-data/classrooms');
    if (res.status && res.data) {
      classRooms.value = res.data;
    } else {
      useToast().error('Gagal memuat daftar ruang kelas.');
    }
  } catch (e) {
    useToast().error('Error saat mengambil data kelas.');
  }
}

const submitStudent = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined);

  try {
    const response = await useApi().post('/admin/students', form);

    if (response.status) {
      useToast().success('Siswa baru berhasil didaftarkan!');
      router.push('/students');
    } else {
      useToast().error(response.message || 'Gagal mendaftarkan siswa.');
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
  fetchClassRooms();
});
</script>