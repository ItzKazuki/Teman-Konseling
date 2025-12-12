<template>
  <div class="bg-white p-7 rounded-xl shadow-lg w-full max-w-sm mx-auto">

    <div class="text-center mb-8">
      <Icon name="tabler:lock-question" class="w-12 h-12 text-primary-600 mx-auto mb-2" />
      <h2 class="text-xl font-bold text-gray-900">Lupa Kata Sandi</h2>
      <p class="text-sm text-gray-600">Masukkan email Anda. Kami akan mengirimkan kode untuk mengatur ulang
        kata sandi Anda.</p>
    </div>

    <form @submit.prevent="handleForgotPassword">
      <div class="mb-5">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <div class="relative">
          <Icon name="tabler:mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input type="email" id="email" v-model="form.email" placeholder="Masukkan email terdaftar Anda"
            class="pl-9 pr-4 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900"
            required />
        </div>
        <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
      </div>

      <button type="submit"
        class="w-full bg-primary-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
        :disabled="loading">
        <span v-if="!loading">Kirim Tautan Reset</span>
        <Icon v-else name="mdi:loading" class="animate-spin w-5 h-5 mx-auto" />
      </button>
    </form>

    <div class="mt-6 text-center">
      <NuxtLink to="/login" class="text-sm font-medium text-gray-600 hover:text-primary-600">
        Kembali ke Login
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'auth'
});

const form = ref({
  email: '',
});

const errors = reactive<{ [key: string]: string[] | undefined }>({});
const loading = ref(false);

const handleForgotPassword = async () => {
  loading.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    const response = await useApi().post('/forgot-password', { email: form.value.email });

    useToast().success(response.message || 'Tautan reset telah dikirim ke email Anda!');

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
    } else if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  } finally {
    loading.value = false;
  }
};
</script>