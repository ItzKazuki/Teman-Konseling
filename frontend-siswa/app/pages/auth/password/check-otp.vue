<template>
  <div class="w-full max-w-sm sm:max-w-md bg-white p-6 sm:p-8 md:p-10">

    <!-- Logo -->
    <div class="flex justify-center mb-10">
      <img
        src="/static/teman-konseling-md.svg"
        alt="Logo Teman Konseling"
        class="h-10 w-auto"
      />
    </div>

    <!-- Title -->
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
      Confirm it’s you
    </h1>

    <p class="text-gray-600 mb-8">
      Enter the 6-digit code sent to your email.
    </p>

    <!-- OTP FORM -->
    <form class="space-y-6" @submit.prevent="handleCheckOtp">

      <label class="block">
        <span class="text-gray-700 text-sm mb-3 block text-center">
          Verification Code
        </span>

        <div class="flex justify-center gap-3">
          <input
            v-for="(digit, index) in otpDigits"
            :key="index"
            :ref="(el) => setOtpRef(el as (HTMLInputElement | null), index)"
            v-model="otpDigits[index]"
            type="text"
            inputmode="numeric"
            maxlength="1"
            @input="handleOtpInput(index, $event)"
            @keydown="handleOtpKeydown(index, $event)"
            class="size-12 md:size-14 text-2xl font-bold text-center rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:bg-gray-100"
            :disabled="loading"
          />
        </div>

        <p
          v-if="otpError"
          class="text-red-600 text-xs mt-3 text-center"
        >
          {{ otpError }}
        </p>
      </label>

      <!-- Resend -->
      <div class="flex justify-end">
        <button
          type="button"
          @click="handleResendOtp"
          class="text-sm text-primary-600 hover:underline disabled:text-gray-400"
          :disabled="loadingResend"
        >
          {{ loadingResend ? 'Resending...' : 'Resend code' }}
        </button>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        :disabled="loading || isOtpIncomplete"
        class="w-full py-3.5 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition disabled:bg-primary-400"
      >
        {{ loading ? 'Verifying...' : 'Verify Code' }}
      </button>
    </form>

    <!-- Footer -->
    <div class="text-center mt-10">
      <NuxtLink
        to="/auth/login"
        class="text-sm font-semibold text-black hover:text-gray-700"
      >
        Back to Login
      </NuxtLink>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'auth' })

const route = useRoute()
const router = useRouter()
const toast = useToast()

const requestId = ref<string | null>(
  typeof route.query.rid === 'string' ? route.query.rid : null
)

const otpDigits = ref<string[]>(['', '', '', '', '', ''])
const otpRefs = ref<(HTMLInputElement | null)[]>([])

const loading = ref(false)
const loadingResend = ref(false)
const otpError = ref<string | null>(null)

const isOtpIncomplete = computed(() =>
  otpDigits.value.some(d => d === '')
)

const setOtpRef = (el: HTMLInputElement | null, index: number) => {
  otpRefs.value[index] = el
}

const handleOtpInput = (index: number, e: Event) => {
  otpError.value = null
  const input = e.target as HTMLInputElement
  const value = input.value.replace(/\D/g, '').slice(0, 1)

  otpDigits.value[index] = value

  if (value && index < otpDigits.value.length - 1) {
    otpRefs.value[index + 1]?.focus()
  }

  if (!isOtpIncomplete.value) {
    handleCheckOtp()
  }
}

const handleOtpKeydown = (index: number, e: KeyboardEvent) => {
  if (e.key === 'Backspace') {
    if (!otpDigits.value[index] && index > 0) {
      otpRefs.value[index - 1]?.focus()
    }
    otpDigits.value[index] = ''
  }
}

const handleCheckOtp = async () => {
  if (!requestId.value || isOtpIncomplete.value || loading.value) {
    otpError.value = 'Please enter the complete 6-digit code.'
    return
  }

  loading.value = true
  otpError.value = null

  try {
    const res = await useApi(false).post(
      '/auth/password/validate-otp',
      {
        request_id: requestId.value,
        otp: otpDigits.value.join('')
      }
    )

    if (res.status) {
      toast.success(res.message || 'Code verified')

      return router.push({
        path: '/auth/password/reset',
        query: { rid: requestId.value }
      })
    }

  } catch (err: any) {
    otpDigits.value = ['', '', '', '', '', '']
    otpRefs.value[0]?.focus()
    otpError.value =
      err?.data?.message || 'Invalid or expired code.'
  } finally {
    loading.value = false
  }
}

const handleResendOtp = async () => {
  if (!requestId.value || loadingResend.value) return

  loadingResend.value = true

  try {
    await useApi(false).post(
      '/auth/password/resend-otp',
      { request_id: requestId.value }
    )

    toast.info('New verification code sent.')
  } catch {
    toast.error('Failed to resend code.')
  } finally {
    setTimeout(() => {
      loadingResend.value = false
    }, 60000)
  }
}

onMounted(() => {
  if (!requestId.value) {
    router.replace('/auth/password/forgot')
    return
  }

  otpRefs.value[0]?.focus()
})
</script>
