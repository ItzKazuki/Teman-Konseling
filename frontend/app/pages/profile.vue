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

        <div>
          <label for="name" class="form-label">Nama Lengkap <span class="text-red-600">*</span></label>
          <input type="text" id="name" v-model="profileForm.name" class="form-input" required
            :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.name }" />
          <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
        </div>

        <div>
          <label for="email" class="form-label">Alamat Email <span class="text-red-600">*</span></label>
          <input type="email" id="email" v-model="profileForm.email" class="form-input" required
            :disabled="isSubmittingProfile" :class="{ 'border-red-500': errors.email }" />
          <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
        </div>

        <div class="flex justify-end">
          <button type="submit" :disabled="isSubmittingProfile"
            class="px-5 py-2.5 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmittingProfile" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Perubahan Profil
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

  </div>
</template>

<script setup lang="ts">
const auth = useAuthStore();

interface UserProfile {
  name: string;
  email: string;
}

interface PasswordPayload {
  current_password: string;
  password: string;
  password_confirmation: string;
}

const profileForm = reactive<UserProfile>({
  name: auth.user?.name ?? '',
  email: auth.user?.email ?? '',
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
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    const response = { status: true, message: 'Profil berhasil diperbarui!' };

    if (response.status) {
      useToast().success(response.message);
    } else {
      useToast().error(response.message || 'Gagal menyimpan profil.');
    }

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
      useToast().error('Validasi gagal. Cek kembali Nama dan Email Anda.');
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan/server.');
    }
  } finally {
    isSubmittingProfile.value = false;
  }
};

const updatePassword = async () => {
  isSubmittingPassword.value = true;
  Object.keys(passwordErrors).forEach(key => passwordErrors[key] = undefined); // Reset errors

  if (passwordForm.password !== passwordForm.password_confirmation) {
    passwordErrors.password_confirmation = ['Konfirmasi kata sandi tidak cocok.'];
    useToast().error('Konfirmasi kata sandi tidak cocok.');
    isSubmittingPassword.value = false;
    return;
  }

  try {
    const response = { status: true, message: 'Kata sandi berhasil diubah!' };

    if (response.status) {
      useToast().success(response.message);
      Object.assign(passwordForm, { current_password: '', password: '', password_confirmation: '' });
    } else {
      useToast().error(response.message || 'Gagal mengubah kata sandi.');
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