<template>
  <div class="bg-white p-7 rounded-xl shadow-lg w-full max-w-sm mx-auto">

    <div class="text-center mb-8">
      <img src="/static/teman-konseling-md.svg" class="mx-auto" alt="TemanKonseling">
      <p class="text-sm text-gray-600">Selamat Datang Kembali</p>
    </div>

    <form @submit.prevent="handleLogin">
      <div class="mb-2">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <div class="relative">
          <Icon name="tabler:mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input type="email" id="email" v-model="form.email" placeholder="Masukkan email Anda"
            class="pl-9 pr-4 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900"
            required />
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <div class="relative">
          <Icon name="tabler:lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input :type="passwordFieldType" id="password" v-model="form.password" placeholder="Masukkan password Anda"
            class="pl-9 pr-10 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900"
            required />
          <button type="button" @click="togglePasswordVisibility"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 flex items-center">
            <Icon :name="passwordFieldType === 'password' ? 'tabler:eye' : 'tabler:eye-off'" class="w-4 h-4" />
          </button>
        </div>
      </div>

      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
          <input id="remember_me" name="remember_me" type="checkbox" v-model="form.remember_me"
            class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" />
          <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
        </div>
        <NuxtLink to="/forgot-password" class="text-sm font-medium text-primary-600 hover:text-primary-500">
          Lupa Password?
        </NuxtLink>
      </div>

      <button type="submit"
        class="w-full bg-primary-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
        :disabled="loading">
        <span v-if="!loading">Login</span>
        <Icon v-else name="mdi:loading" class="animate-spin w-5 h-5 mx-auto" />
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'auth'
});

const auth = useAuthStore();

const form = ref < LoginRequest > ({
  email: '',
  password: '',
  remember_me: false,
})
const passwordFieldType = ref('password')
const loading = ref(false)

const togglePasswordVisibility = () => {
  passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password'
}

const handleLogin = async () => {
  loading.value = true
  try {
    await auth.login(form.value);
    useToast().success(`Login berhasil, Selamat datang ${auth.user?.name}!`)
    return navigateTo('/dashboard');
  } catch (err: any) {
    if (err?.data?.errors) {
      // setErrors(err.data.errors);
    } else if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  } finally {
    loading.value = false;
  }

}
</script>