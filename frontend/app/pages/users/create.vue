<template>
  <div class="space-y-6">

    <div class="flex items-center">
      <Icon name="tabler:user-plus" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Tambah Pengguna Baru
      </h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

      <form @submit.prevent="submitUser" class="space-y-6">

        <section class="space-y-4 border-b pb-4">
          <h2 class="text-xl font-semibold text-gray-800">Detail Akun</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="form-label">Nama Lengkap <span class="text-red-600">*</span></label>
              <input type="text" id="name" v-model="form.name" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.name }" />
              <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
            </div>

            <div>
              <label for="email" class="form-label">Email <span class="text-red-600">*</span></label>
              <input type="email" id="email" v-model="form.email" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.email }" />
              <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="password" class="form-label">Kata Sandi Awal <span class="text-red-600">*</span></label>
              <input type="password" id="password" v-model="form.password" class="form-input" required
                :disabled="isSubmitting" :class="{ 'border-red-500': errors.password }" />
              <p v-if="errors.password" class="mt-1 text-xs text-red-500">{{ errors.password[0] }}</p>
            </div>

            <div>
              <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi
                <span class="text-red-600">*</span></label>
              <input type="password" id="password_confirmation" v-model="form.password_confirmation" class="form-input"
                required :disabled="isSubmitting" />
            </div>
          </div>
        </section>

        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-gray-800">Detail Pekerjaan & Kontak</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="nip" class="form-label">NIP <span class="text-red-600">*</span></label>
              <input type="text" id="nip" v-model="form.nip" class="form-input" required :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.nip }" />
              <p v-if="errors.nip" class="mt-1 text-xs text-red-500">{{ errors.nip[0] }}</p>
            </div>

            <div>
              <label for="jabatan" class="form-label">Jabatan <span class="text-red-600">*</span></label>
              <input type="text" id="jabatan" v-model="form.jabatan" class="form-input" required
                :disabled="isSubmitting" :class="{ 'border-red-500': errors.jabatan }" />
              <p v-if="errors.jabatan" class="mt-1 text-xs text-red-500">{{ errors.jabatan[0] }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="phone_number" class="form-label">Nomor Telepon <span class="text-red-600">*</span></label>
              <input type="text" id="phone_number" v-model="form.phone_number" class="form-input" required
                :disabled="isSubmitting" :class="{ 'border-red-500': errors.phone_number }" />
              <p v-if="errors.phone_number" class="mt-1 text-xs text-red-500">{{ errors.phone_number[0] }}</p>
            </div>

            <FormSelect name="role" label="Peran (Role)" v-model="form.role" :options="[
              { value: '', label: 'Pilih Peran' },
              { value: 'guru', label: 'Guru' },
              { value: 'bk', label: 'BK/Konselor' },
              { value: 'staff', label: 'Staff' },
            ]" required :disabled="isSubmitting" />
          </div>

          <div class="flex flex-col">
            <label class="form-label mb-2">Status Ketersediaan</label>
            <div class="flex items-center space-x-3 h-full">
              <button type="button" @click="form.is_available = form.is_available === 1 ? 0 : 1"
                :class="form.is_available === 1 ? 'bg-primary-600' : 'bg-gray-300'"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                :disabled="isSubmitting">
                <span :class="form.is_available === 1 ? 'translate-x-6' : 'translate-x-1'"
                  class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" />
              </button>
              <span class="text-sm font-medium text-gray-700">
                {{ form.is_available === 1 ? 'Tersedia' : 'Tidak Tersedia' }}
              </span>
            </div>
            <p class="mt-1 text-[10px] text-gray-500 italic">Khusus untuk role BK/Konselor guna status layanan.</p>
          </div>
        </section>

        <div class="pt-6 border-t flex justify-end space-x-3">
          <NuxtLink to="/users"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Batal
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting"
            class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Buat Akun Pengguna
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup lang="ts">
const router = useRouter();

interface UserCreationPayload {
  name: string;
  email: string;
  nip: string;
  role: 'bk' | 'guru' | 'staff' | string;
  jabatan: string;
  phone_number: string;
  is_available: number;
  password: string;
  password_confirmation: string;
}

const initialForm: UserCreationPayload = {
  name: '',
  email: '',
  nip: '',
  role: '',
  jabatan: '',
  phone_number: '',
  is_available: 1,
  password: '',
  password_confirmation: '',
};

const form = reactive<UserCreationPayload>({ ...initialForm });
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmitting = ref(false);

const submitUser = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined);

  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = ['Konfirmasi kata sandi tidak cocok.'];
    useToast().error('Konfirmasi kata sandi tidak cocok.');
    isSubmitting.value = false;
    return;
  }

  try {
    const response = await useApi().post('/admin/users', form);

    if (response.status) {
      useToast().success('Pengguna baru berhasil ditambahkan!');
      router.push('/admin/users');
    } else {
      useToast().error(response.message || 'Gagal membuat pengguna.');
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
</script>