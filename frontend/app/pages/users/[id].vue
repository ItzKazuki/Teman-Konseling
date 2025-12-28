<template>
  <div class="space-y-6">

    <div class="flex items-center">
      <Icon name="tabler:user-edit" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Edit Pengguna: {{ form.name || 'Memuat...' }}
      </h1>
    </div>

    <div v-if="isLoading" class="p-8 text-center text-gray-500 bg-white rounded-xl shadow-lg border border-gray-200">
      <Icon name="tabler:loader-2" class="w-8 h-8 animate-spin mx-auto text-primary-600" />
      <p class="mt-2">Memuat data pengguna...</p>
    </div>

    <div v-else class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

      <form @submit.prevent="submitUserUpdate" class="space-y-6">

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
        </section>

        <section class="space-y-4">
          <h2 class="text-xl font-semibold text-gray-800">Detail Pekerjaan & Peran</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="nip" class="form-label">NIP</label>
              <input type="text" id="nip" v-model="form.nip" class="form-input" :disabled="isSubmitting"
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

          <FormSelect name="role" label="Peran (Role)" v-model="form.role" :options="[
            { value: '', label: 'Pilih Peran' },
            { value: 'guru', label: 'Guru' },
            { value: 'bk', label: 'BK/Konselor' },
            { value: 'admin', label: 'Administrator' },
          ]" required :disabled="isSubmitting" />
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
              <button type="button" @click="form.is_available = !form.is_available"
                :class="form.is_available ? 'bg-primary-600' : 'bg-gray-300'"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                :disabled="isSubmitting">
                <span :class="form.is_available ? 'translate-x-6' : 'translate-x-1'"
                  class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" />
              </button>
              <span class="text-sm font-medium text-gray-700">
                {{ form.is_available ? 'Tersedia' : 'Tidak Tersedia' }}
              </span>
            </div>
          </div>
        </section>

        <section class="space-y-4 pt-4 border-t">
          <h2 class="text-xl font-semibold text-gray-800">Reset Kata Sandi</h2>
          <p class="text-sm text-gray-500">Anda dapat mengatur ulang kata sandi pengguna ini jika diperlukan.</p>
          <button type="button" @click="resetPassword" :disabled="isSubmitting"
            class="flex items-center justify-center px-4 py-2 text-sm font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors disabled:bg-red-300">
            <Icon name="tabler:key" class="w-4 h-4 mr-1" /> Reset Kata Sandi
          </button>
          <p v-if="resetPasswordMessage" class="mt-2 text-sm"
            :class="resetPasswordMessage.status === 'success' ? 'text-green-600' : 'text-red-600'">
            {{ resetPasswordMessage.message }}
          </p>
        </section>

        <div class="flex justify-end space-x-3">
          <NuxtLink to="/users"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Batal
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting || isLoading"
            class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Perubahan Pengguna
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const router = useRouter();

const userId = route.params.id as string;
if (!userId) {
  router.replace('/users');
}

interface UserUpdatePayload {
  name: string;
  email: string;
  nip: string;
  role: 'bk' | 'guru' | 'staff' | string;
  jabatan: string;
  phone_number: string;
  is_available: boolean;
}

const initialForm: UserUpdatePayload = {
  name: '',
  email: '',
  nip: '',
  role: '',
  jabatan: '',
  phone_number: '',
  is_available: true,
};

const form = reactive<UserUpdatePayload>({ ...initialForm });
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmitting = ref(false);
const isLoading = ref(true);

const resetPasswordMessage = ref<{ status: 'success' | 'error', message: string } | null>(null);

async function fetchUserData() {
  isLoading.value = true;
  try {
    const res = await useApi().get<User>(`/admin/users/${userId}`);
    if (res.status && res.data) {
      const data = res.data;
      Object.assign(form, {
        name: data.name,
        email: data.email,
        nip: data.nip || '',
        role: data.role,
        jabatan: data.jabatan,
        phone_number: data.phone || '',
        is_available: data.is_available ?? 1,
      });
    } else {
      useToast().error('Data pengguna tidak ditemukan.');
      router.replace('/users');
    }
  } catch (e) {
    useToast().error('Terjadi kesalahan saat memuat data.');
    router.replace('/users');
  } finally {
    isLoading.value = false;
  }
}

const submitUserUpdate = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    const response = await useApi().put(`/admin/users/${userId}`, form);

    if (response.status) {
      useToast().success('Data pengguna berhasil diperbarui!');
      router.push('/admin/users');
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

const resetPassword = async () => {
  isSubmitting.value = true;
  resetPasswordMessage.value = null;

  try {
    const confirmed = await useAlert().confirm('Apakah Anda yakin ingin mereset kata sandi pengguna ini? Kata sandi baru akan di-generate oleh sistem.');

    if (!confirmed) {
      isSubmitting.value = false;
      return;
    }

    const response = await useApi().post(`/admin/users/${userId}/reset-password`);

    if (response.status) {
      resetPasswordMessage.value = { status: 'success', message: response.message || 'Kata sandi berhasil direset. Harap informasikan kepada pengguna.' };
      useToast().success('Kata sandi berhasil direset!');
    } else {
      resetPasswordMessage.value = { status: 'error', message: response.message || 'Gagal mereset kata sandi.' };
      useToast().error('Gagal mereset kata sandi.');
    }

  } catch (err: any) {
    resetPasswordMessage.value = { status: 'error', message: err.data?.message || 'Terjadi kesalahan saat mereset kata sandi.' };
    useToast().error('Terjadi kesalahan saat mereset kata sandi.');
  } finally {
    isSubmitting.value = false;
  };
}

onMounted(() => {
  fetchUserData();
});
</script>