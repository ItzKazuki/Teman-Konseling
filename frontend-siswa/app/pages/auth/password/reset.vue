<template>
  <div class="w-full max-w-sm sm:max-w-md bg-white p-6 sm:p-8 md:p-10">

    <div class="flex justify-center mb-10">
      <img src="/static/teman-konseling-md.svg" alt="Logo Teman Konseling" class="h-10 w-auto">
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h1>
    <p class="text-gray-600 mb-8">
      Set your new password below.
    </p>

    <form class="space-y-6" @submit.prevent="handleResetPassword">

      <div class="relative">
        <label for="password" class="font-normal text-base block mb-1">New Password</label>
        <input id="password" :type="passwordFieldType" v-model="form.password" :disabled="loading"
          class="w-full border border-gray-300 p-3 pr-10 rounded-lg focus:border-gray-500 focus:ring-0 focus:outline-none placeholder-gray-400 disabled:bg-gray-50 disabled:cursor-not-allowed"
          placeholder="New Password">
        <button type="button" @click="togglePasswordVisibility" :disabled="loading"
          class="absolute inset-y-0 right-0 top-6 flex items-center pr-3 text-gray-400 hover:text-gray-600 disabled:hover:text-gray-400">
          <svg v-if="passwordFieldType === 'password'" class="w-5 h-5" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
            </path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7 1.274-4.057 5.065-7 9.542-7 1.141 0 2.228.188 3.239.52M1 1l22 22">
            </path>
          </svg>
        </button>
      </div>

      <div class="relative">
        <label for="password_confirmation" class="font-normal text-base block mb-1">Confirm Password</label>
        <input id="password_confirmation" :type="passwordFieldType" v-model="form.password_confirmation"
          :disabled="loading"
          class="w-full border border-gray-300 p-3 pr-10 rounded-lg focus:border-gray-500 focus:ring-0 focus:outline-none placeholder-gray-400 disabled:bg-gray-50 disabled:cursor-not-allowed"
          placeholder="Confirm Password">
      </div>

      <button type="submit" :disabled="loading || !isFormValid"
        class="w-full py-3.5 bg-primary-600 text-white rounded-lg font-semibold shadow-md hover:bg-primary-700 focus:ring-2 focus:ring-primary-400 transition duration-150 disabled:bg-primary-400 disabled:cursor-not-allowed flex items-center justify-center">
        <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
          </path>
        </svg>
        Reset Password
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
definePageMeta({ layout: 'auth' })

/* ------------------ IMPORT ------------------ */
const route = useRoute()
const router = useRouter()
const toast = useToast()

/* ------------------ REQUEST ID ------------------ */
const requestId =
  typeof route.query.rid === 'string' ? route.query.rid : null

/* ------------------ STATE ------------------ */
const loading = ref(false)
const errorMessage = ref<string | null>(null)

const form = ref({
  password: '',
  password_confirmation: ''
})

/* ------------------ PASSWORD TOGGLE ------------------ */
const passwordFieldType = ref<'password' | 'text'>('password')

const togglePasswordVisibility = () => {
  passwordFieldType.value =
    passwordFieldType.value === 'password' ? 'text' : 'password'
}

/* ------------------ VALIDATION ------------------ */
const isFormValid = computed(() =>
  form.value.password.length >= 8 &&
  form.value.password === form.value.password_confirmation
)

/* ------------------ SUBMIT ------------------ */
const handleResetPassword = async () => {
  if (!requestId || !isFormValid.value || loading.value) {
    errorMessage.value = 'Invalid request.'
    return
  }

  loading.value = true
  errorMessage.value = null

  try {
    const res = await useApi(false).post(
      '/auth/password/reset',
      {
        request_id: requestId,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation
      }
    )

    if (res.status) {
      toast.success(
        res.message || 'Password successfully reset.'
      )

      return router.replace('/auth/login')
    }

  } catch (err: any) {
    errorMessage.value =
      err?.data?.message || 'Failed to reset password.'
  } finally {
    loading.value = false
  }
}

/* ------------------ GUARD ------------------ */
onMounted(() => {
  if (!requestId) {
    toast.error('Invalid or expired reset session.')
    router.replace('/auth/password/forgot')
  }
})
</script>