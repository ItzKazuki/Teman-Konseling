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

      <div class="flex flex-col lg:flex-row gap-8">

        <div
          class="flex flex-col items-center space-y-4 w-full lg:w-1/3 border-b lg:border-b-0 lg:border-r border-gray-100 pb-6 lg:pb-0 lg:pr-8">
          <label class="form-label text-center">Foto Profil</label>
          <div class="relative group">
            <img :src="avatarPreview || auth.user?.avatar_url || '/images/default-avatar.png'"
              class="w-40 h-40 rounded-full object-cover border-4 border-primary-50 shadow-md" alt="Avatar" />
            <label for="avatar-upload"
              class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
              <Icon name="tabler:camera" class="w-8 h-8 text-white" />
            </label>
            <input id="avatar-upload" type="file" class="hidden" accept="image/*" @change="handleAvatarChange" />
          </div>
          <p class="text-xs text-gray-500 text-center">Klik gambar untuk mengubah foto profil (Max file size: 2MB)</p>

          <!-- <div class="flex items-center space-x-2 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
            <span :class="profileForm.is_available ? 'bg-green-500' : 'bg-red-500'"
              class="w-2.5 h-2.5 rounded-full"></span>
            <span class="text-xs font-medium text-gray-600">
              {{ profileForm.is_available ? 'Sedang Online' : 'Sedang Sibuk' }}
            </span>
          </div> -->
        </div>

        <form @submit.prevent="updateProfile" class="space-y-6 w-full">

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

            <div v-if="can('bk')" class="flex flex-col justify-center">
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

  <ModalCropper :show="showCropper" :src="imageSource" :aspect-ratio="1" @close="showCropper = false"
    @cropped="onAvatarCropped" />
</template>

<script setup lang="ts">
const { can } = usePermission();
const auth = useAuthStore();
const config = useRuntimeConfig();

interface UserProfile {
  nip: string;
  name: string;
  email: string;
  jabatan: string;
  phone_number: string;
  is_available: boolean;
  avatar_file_id?: string;
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

const avatarFile = ref<File | null>(null);
const avatarPreview = ref<string | null>(null);
const imageSource = ref<any>(null);
const showCropper = ref(false);

const handleAvatarChange = (event: any) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (file.size > 2 * 1024 * 1024) {
    useToast().error('Ukuran file maksimal 2MB');
    return;
  }

  const reader = new FileReader();
  reader.onload = () => {
    imageSource.value = reader.result;
    showCropper.value = true;
  };
  reader.readAsDataURL(file);
};

const onAvatarCropped = (base64: string) => {
  avatarPreview.value = base64;
  avatarFile.value = base64ToFile(base64, "avatar.jpeg");
  showCropper.value = false;
};

const updateProfile = async () => {
  isSubmittingProfile.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined);

  try {
    if (avatarFile.value) {
      const fileId = await useFile().uploadAsset(avatarFile.value, 'avatars', 'public');
      if (fileId) {
        profileForm.avatar_file_id = fileId;
      }
    }

    const response = await useApi().put<User>('/profile/update', profileForm);

    if (response.status) {
      useToast().success('Profil berhasil diperbarui');
      auth.setUser(response.data);
      avatarFile.value = null;
    }

  } catch (err: any) {
    useToast().error(err.data?.message || 'Gagal memperbarui profil');
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
    const response = await useApi().put<User>('/profile/password/update', passwordForm);

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