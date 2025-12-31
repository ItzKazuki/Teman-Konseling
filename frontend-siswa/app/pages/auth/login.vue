<template>
  <div class="w-full max-w-sm sm:max-w-md bg-white p-6 sm:p-8 md:p-10">

    <div class="flex justify-center mb-10">
      <img src="/static/teman-konseling-md.svg" alt="Logo Teman Konseling" class="h-10 w-auto">
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Hi, Welcome!</h1>

    <form class="space-y-6" @submit.prevent="handleLogin">
      <div>
        <label for="email" class="font-normal text-base block mb-1">Email address</label>
        <input id="email" type="email" v-model="form.email"
          class="w-full border border-gray-300 p-3 rounded-lg focus:border-gray-500 focus:ring-0 focus:outline-none placeholder-gray-400"
          placeholder="Your email">
      </div>

      <div>
        <label for="password" class="font-normal text-base block mb-1">Password</label>
        <div class="relative">
          <input id="password" :type="passwordFieldType" v-model="form.password"
            class="w-full border border-gray-300 p-3 pr-10 rounded-lg focus:border-gray-500 focus:ring-0 focus:outline-none placeholder-gray-400"
            placeholder="Password">
          <button type="button" @click="togglePasswordVisibility"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
            <Icon :name="passwordFieldType === 'password' ? 'tabler:eye' : 'tabler:eye-off'" class="w-5 h-5" />
          </button>
        </div>
      </div>


      <div class="flex justify-between items-center text-sm">
        <div class="flex items-center">
          <input id="remember-me" type="checkbox" v-model="form.remember_me"
            class="h-4 w-4 text-primary-600 border-gray-300 rounded" />
          <label for="remember-me" class="ml-2 text-gray-700">
            Remember me
          </label>
        </div>

        <NuxtLink to="/auth/password/forgot" class="font-medium text-primary-600 hover:text-primary-700">
          Forgot password?
        </NuxtLink>
      </div>

      <button type="submit" :disabled="loading" :class="[
        'w-full py-3.5 rounded-lg font-semibold transition-all duration-200 flex justify-center items-center gap-2',
        loading
          ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
          : 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400'
      ]">
        <span v-if="loading" class="flex items-center gap-2">
          <span class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
          Logging in...
        </span>
        <span v-else>Log in</span>
      </button>
    </form>

    <!-- <div class="flex items-center my-6">
        <div class="grow border-t border-gray-300"></div>
        <span class="shrink mx-4 text-gray-500 text-sm">Or with</span>
        <div class="grow border-t border-gray-300"></div>
      </div>

      <div class="flex space-x-4">
        
        <button class="w-1/2 flex items-center justify-center py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-150">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h3.295l-.527 2.986h-2.768v7.01C18.343 21.128 22 16.991 22 12z"></path></svg>
          Facebook
        </button>
        
        <button class="w-1/2 flex items-center justify-center py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-150">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.000 4.800a7.207 7.207 0 015.143 2.006l-1.928 1.928c-1.077-.962-2.478-1.547-3.215-1.547-2.618 0-4.743 2.125-4.743 4.743s2.125 4.743 4.743 4.743c1.373 0 2.651-.624 3.528-1.562l1.928 1.928A8.887 8.887 0 0112 21.6c-4.969 0-9-4.031-9-9s4.031-9 9-9z" fill="#EA4335"/><path d="M21.000 12.000c0-.829-.071-1.631-.202-2.416H12v4.832h4.943c-.227 1.128-.888 2.115-1.789 2.871l2.45 2.378A8.956 8.956 0 0021 12.000z" fill="#4285F4"/><path d="M12 21.600c2.811 0 5.253-.94 7.000-2.585l-2.45-2.378c-.97.77-2.19 1.229-3.483 1.229-2.618 0-4.743-2.125-4.743-4.743s2.125-4.743 4.743-4.743c1.373 0 2.651-.624 3.528-1.562L17.143 4.8A8.898 8.898 0 0012 2.400c-4.969 0-9 4.031-9 9s4.031 9 9 9z" fill="#FBBC05"/><path d="M5.385 12c0-1.168.204-2.296.586-3.351L3.543 6.643A8.995 8.995 0 003 12c0 1.957.514 3.791 1.41 5.378l2.193-1.897C5.589 14.187 5.385 13.125 5.385 12z" fill="#34A853"/></svg>
          Google
        </button>
      </div> -->

    <div class="text-center mt-10">
      <p class="text-gray-500 text-sm">Don’t have an account?
        <NuxtLink to="/auth/register" class="text-black font-semibold hover:text-gray-800">Sign up</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'auth'
})

const loading = ref<boolean>(false);
const auth = useAuthStore();

const form = ref<LoginRequest>({
  email: '',
  password: '',
  remember_me: false
});

const passwordFieldType = ref('password');

const togglePasswordVisibility = () => {
  passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password';
};

const handleLogin = async () => {
  if (loading.value) return

  loading.value = true
  try {
    await auth.login(form.value)
    useToast().success(`Login berhasil, selamat datang ${auth.user?.name}!`)
    await navigateTo('/home')
  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message)
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  } finally {
    loading.value = false
  }
}
</script>