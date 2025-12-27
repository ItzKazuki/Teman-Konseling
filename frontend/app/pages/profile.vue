<template>
  <div class="space-y-8">

    <div class="flex items-center">
      <Icon name="tabler:user-circle" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Pengaturan Akun
      </h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Informasi Akun</h2>

      <form @submit.prevent="updateProfile" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="name" class="form-label">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" id="name" v-model="profileForm.name" class="form-input" required
              :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.name }" />
            <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
          </div>

          <div>
            <label for="nip" class="form-label">NIP <span class="text-red-600">*</span></label>
            <input type="text" id="nip" v-model="profileForm.nip" class="form-input" required
              :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.nip }" />
            <p v-if="errors.nip" class="mt-1 text-xs text-red-500">{{ errors.nip[0] }}</p>
          </div>

          <div>
            <label for="email" class="form-label">Alamat Email <span class="text-red-600">*</span></label>
            <input type="email" id="email" v-model="profileForm.email" class="form-input" required
              :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.email }" />
            <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
          </div>

          <div>
            <label for="phone_number" class="form-label">Nomor Telepon <span class="text-red-600">*</span></label>
            <input type="text" id="phone_number" v-model="profileForm.phone_number" class="form-input" required
              :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.phone_number }" />
            <p v-if="errors.phone_number" class="mt-1 text-xs text-red-500">{{ errors.phone_number[0] }}</p>
          </div>

          <div>
            <label for="jabatan" class="form-label">Jabatan <span class="text-red-600">*</span></label>
            <input type="text" id="jabatan" v-model="profileForm.jabatan" class="form-input" required
              :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.jabatan }" />
            <p v-if="errors.jabatan" class="mt-1 text-xs text-red-500">{{ errors.jabatan[0] }}</p>
          </div>

          <div class="flex flex-col justify-center">
            <label class="form-label mb-2">Status Ketersediaan Konseling</label>
            <div class="flex items-center space-x-3 h-full">
              <button type="button" @click="profileForm.is_available = !profileForm.is_available"
                :class="profileForm.is_available ? 'bg-primary-600' : 'bg-gray-300'"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                <span :class="profileForm.is_available ? 'translate-x-6' : 'translate-x-1'"
                  class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" />
              </button>
              <span class="text-sm font-medium text-gray-700">
                {{ profileForm.is_available ? 'Tersedia' : 'Sibuk / Tidak Tersedia' }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex justify-end pt-4">
          <button type="submit" :disabled="isSubmittingProfile"
            class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmittingProfile" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Ganti Kata Sandi</h2>
      <p class="text-xs text-gray-500 mb-4">Pastikan kata sandi baru Anda kuat dan belum pernah digunakan.</p>

      <form @submit.prevent="updatePassword" class="space-y-6">
        <div>
          <label for="current_password" class="form-label">Kata Sandi Saat Ini <span
              class="text-red-600">*</span></label>
          <input type="password" id="current_password" v-model="passwordForm.current_password" class="form-input"
            required :disabled="isSubmittingPassword" :class="{ 'border-red-500': passwordErrors.current_password }" />
          <p v-if="passwordErrors.current_password" class="mt-1 text-xs text-red-500">{{
            passwordErrors.current_password[0] }}</p>
        </div>

        <div>
          <label for="password" class="form-label">Kata Sandi Baru <span class="text-red-600">*</span></label>
          <input type="password" id="password" v-model="passwordForm.password" class="form-input" required
            :disabled="isSubmittingPassword" :class="{ 'border-red-500': passwordErrors.password }" />
          <p v-if="passwordErrors.password" class="mt-1 text-xs text-red-500">{{ passwordErrors.password[0] }}</p>
        </div>

        <div>
          <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru <span
              class="text-red-600">*</span></label>
          <input type="password" id="password_confirmation" v-model="passwordForm.password_confirmation"
            class="form-input" required :disabled="isSubmittingPassword"
            :class="{ 'border-red-500': passwordErrors.password_confirmation }" />
          <p v-if="passwordErrors.password_confirmation" class="mt-1 text-xs text-red-500">{{
            passwordErrors.password_confirmation[0] }}</p>
        </div>

        <div class="flex justify-end">
          <button type="submit" :disabled="isSubmittingPassword"
            class="px-5 py-2.5 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md disabled:bg-red-400">
            <Icon v-if="isSubmittingPassword" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Ubah Kata Sandi
          </button>
        </div>
      </form>
    </div>

    <p class="text-center text-gray-500 text-xs">{{ config.public.buildVersion }}</p>

  </div>
</template>

<script setup lang="ts">
const auth = useAuthStore();
const config = useRuntimeConfig();

interface UserProfile {
  nip: string;
  name: string;
  email: string;
  jabatan: string;
  phone_number: string;
  is_available: boolean;
}

interface PasswordPayload {
  current_password: string;
  password: string;
  password_confirmation: string;
}

const profileForm = reactive<UserProfile>({
  nip: auth.user?.nip ?? '',
  name: auth.user?.name ?? '',
  email: auth.user?.email ?? '',
  jabatan: auth.user?.jabatan ?? '',
  phone_number: auth.user?.phone ?? '',
  is_available: auth.user?.is_available ?? true,
});

const passwordForm = reactive<PasswordPayload>({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const errors = reactive<{ [key: string]: string[] | undefined }>({});
const passwordErrors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmittingProfile = ref(false);
const isSubmittingPassword = ref(false);

const updateProfile = async () => {
  isSubmittingProfile.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined);

  try {
    const response = await useApi().put<User>('/admin/profile/update', profileForm);

    if (response.status) {
      useToast().success(response.message);
      auth.setUser(response.data);
    }

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
      useToast().error('Validasi gagal. Mohon periksa kembali data Anda.');
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan/server.');
    }
  } finally {
    isSubmittingProfile.value = false;
  }
};

const updatePassword = async () => {
  isSubmittingPassword.value = true;
  Object.keys(passwordErrors).forEach(key => passwordErrors[key] = undefined);

  if (passwordForm.password !== passwordForm.password_confirmation) {
    passwordErrors.password_confirmation = ['Konfirmasi kata sandi tidak cocok.'];
    useToast().error('Konfirmasi kata sandi tidak cocok.');
    isSubmittingPassword.value = false;
    return;
  }

  try {
    const response = await useApi().put<User>('/admin/profile/password/update', passwordForm);

    if (response.status) {
      useToast().success(response.message);
      Object.assign(passwordForm, { current_password: '', password: '', password_confirmation: '' });
    }
  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(passwordErrors, err.data.errors);
      useToast().error('Gagal. Cek kembali Kata Sandi Saat Ini dan yang Baru.');
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan/server.');
    }
  } finally {
    isSubmittingPassword.value = false;
  }
};
</script>