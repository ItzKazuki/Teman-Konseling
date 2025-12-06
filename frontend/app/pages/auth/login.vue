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
          <input type="email" id="email" v-model="email" placeholder="Masukkan email Anda"
            class="pl-9 pr-4 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
            required />
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <div class="relative">
          <Icon name="tabler:lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input :type="passwordFieldType" id="password" v-model="password" placeholder="Masukkan password Anda"
            class="pl-9 pr-10 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
            required />
          <button type="button" @click="togglePasswordVisibility"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 flex items-center">
            <Icon :name="passwordFieldType === 'password' ? 'tabler:eye' : 'tabler:eye-off'" class="w-4 h-4" />
          </button>
        </div>
      </div>

      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
          <input id="remember_me" name="remember_me" type="checkbox"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
          <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
        </div>
        <NuxtLink to="/forgot-password" class="text-sm font-medium text-blue-600 hover:text-blue-500">
          Lupa Password?
        </NuxtLink>
      </div>

      <button type="submit"
        class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
        :disabled="loading">
        <span v-if="!loading">Login</span>
        <Icon v-else name="mdi:loading" class="animate-spin w-5 h-5 mx-auto" />
      </button>
    </form>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'auth'
});
// Data properties
const email = ref('')
const password = ref('')
const passwordFieldType = ref('password')
const loading = ref(false)

// Methods
const togglePasswordVisibility = () => {
  passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password'
}

const handleLogin = async () => {
  loading.value = true
  console.log('Login attempt:', { email: email.value, password: password.value })
  try {
    await new Promise(resolve => setTimeout(resolve, 1500));
    await navigateTo('/dashboard');
  } catch (error) {
    console.error('Login error:', error)
  } finally {
    loading.value = false
  }
}
</script>