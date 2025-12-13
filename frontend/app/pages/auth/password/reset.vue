<template>
  <div class="bg-white p-7 rounded-xl shadow-lg w-full max-w-sm mx-auto">

    <div class="text-center mb-8">
      <Icon name="tabler:lock-access" class="w-12 h-12 text-primary-600 mx-auto mb-2" />
      <h2 class="text-xl font-bold text-gray-900">Atur Ulang Kata Sandi</h2>
      <p class="text-sm text-gray-600">Masukkan kata sandi baru untuk akun {{ form.email }}.</p>
    </div>

    <form @submit.prevent="handleResetPassword">

      <input type="hidden" v-model="form.email" />
      <input type="hidden" v-model="form.reset_token" />

      <div class="mb-2">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
        <div class="relative">
          <Icon name="tabler:lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input :type="passwordFieldType" id="password" v-model="form.password" placeholder="Minimal 8 karakter"
            class="pl-9 pr-10 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900"
            required />
          <button type="button" @click="togglePasswordVisibility"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 flex items-center">
            <Icon :name="passwordFieldType === 'password' ? 'tabler:eye' : 'tabler:eye-off'" class="w-4 h-4" />
          </button>
        </div>
        <p v-if="errors.password" class="mt-1 text-xs text-red-500">{{ errors.password[0] }}</p>
      </div>

      <div class="mb-5">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata
          Sandi</label>
        <div class="relative">
          <Icon name="tabler:lock-check"
            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input type="password" id="password_confirmation" v-model="form.password_confirmation"
            placeholder="Ulangi kata sandi baru"
            class="pl-9 pr-4 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900"
            required />
        </div>
        <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-500">{{ errors.password_confirmation?.[0] }}
        </p>
      </div>

      <button type="submit"
        class="w-full bg-primary-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
        :disabled="loading">
        <span v-if="!loading">Ubah Kata Sandi</span>
        <Icon v-else name="mdi:loading" class="animate-spin w-5 h-5 mx-auto" />
      </button>
    </form>

    <div class="mt-6 text-center">
      <NuxtLink to="/login" class="text-sm font-medium text-gray-600 hover:text-primary-600">
        Sudah ingat? Kembali ke Login
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'auth'
});

const route = useRoute();
const router = useRouter();

const email = route.query.email as string || '';
const token = route.query.token as string || '';

if (!email || !token) {
  useToast().error('Tautan reset tidak valid atau kedaluwarsa.');
  router.replace('/forgot-password');
}

const form = ref({
  email: email,
  reset_token: token,
  password: '',
  password_confirmation: '',
});

const passwordFieldType = ref('password')
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const loading = ref(false);

const togglePasswordVisibility = () => {
  passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password'
}

const handleResetPassword = async () => {
  loading.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined);

  try {
    // 💡 Asumsi: Endpoint untuk reset password adalah POST /api/v1/reset-password
    const response = await useApi().post('/auth/password/reset', form.value);

    useToast().success(response.message || 'Kata sandi berhasil diatur ulang! Silakan login.');

    // Arahkan ke halaman login setelah sukses
    return navigateTo('/login');

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
    } else if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Reset kata sandi gagal. Coba lagi.')
    }
  } finally {
    loading.value = false;
  }
};
</script>