<template>
  <div class="w-full max-w-sm sm:max-w-md bg-white p-6 sm:p-8 md:p-10">

    <div class="flex justify-center mb-10">
      <img src="/static/teman-konseling-md.svg" alt="Logo Teman Konseling" class="h-10 w-auto">
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
    <p class="text-gray-600 mb-8">
      Enter your email address to receive a verification code.
    </p>

    <form class="space-y-6" @submit.prevent="handleForgotPassword">

      <div>
        <label for="email" class="font-normal text-base block mb-1">Email address</label>
        <input id="email" type="email" v-model="form.email" :disabled="loading"
          class="w-full border border-gray-300 p-3 rounded-lg focus:border-gray-500 focus:ring-0 focus:outline-none placeholder-gray-400 disabled:bg-gray-50 disabled:cursor-not-allowed"
          placeholder="Your email">
      </div>

      <button type="submit" :disabled="loading"
        class="w-full py-3.5 bg-primary-600 text-white rounded-lg font-semibold shadow-md hover:bg-primary-700 focus:ring-2 focus:ring-primary-400 transition duration-150 disabled:bg-primary-400 disabled:cursor-not-allowed flex items-center justify-center">
        <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
          </path>
        </svg>
        Send Verification Code
      </button>
    </form>

    <div class="text-center mt-10">
      <p class="text-gray-500 text-sm">
        <NuxtLink to="/auth/login" class="text-black font-semibold hover:text-gray-800">Back to Login</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'auth'
})

interface ForgotPasswordRequest {
  email: string;
}

const loading = ref(false);

const form = ref<ForgotPasswordRequest>({
  email: ''
});

const router = useRouter();

const handleForgotPassword = async () => {
  loading.value = true;

  try {
    const res = await useApi(false).post('/auth/password/forgot', {
      email: form.value.email
    });

    if (!res.status) return;

    useToast().success(
      res.message || 'Kode verifikasi telah dikirim ke email.'
    );

    router.push({
      path: '/auth/password/check-otp',
      query: {
        rid: res.data.request_id
      }
    });

  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('An error occurred. Please try again.');
    }
  } finally {
    loading.value = false;
  }
};
</script>
