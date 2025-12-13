<template>
  <div class="bg-white p-7 rounded-xl shadow-lg w-full max-w-sm mx-auto text-center">

    <div class="text-center mb-8">
      <Icon name="tabler:lock-question" class="w-12 h-12 text-primary-600 mx-auto mb-2" />

      <template v-if="page === 'forgot'">
        <h2 class="text-xl font-bold text-gray-900">Lupa Kata Sandi?</h2>
        <p class="text-sm text-gray-600">Masukkan email Anda untuk menerima kode reset.</p>
      </template>

      <template v-else>
        <h2 class="text-xl font-bold text-gray-900">Konfirmasi Identitas</h2>
        <p class="text-sm text-gray-600">Masukkan 6-digit kode yang telah dikirim ke email anda.</p>
      </template>
    </div>

    <template v-if="page === 'forgot'">
      <form @submit.prevent="onRequestForgotPassword" class="space-y-5">
        <label class="block">
          <span class="block text-sm font-medium text-gray-700 mb-2 text-left">Email</span>
          <input v-model="formForgot.email" type="email" required placeholder="Email terdaftar"
            class="pl-4 pr-4 py-2 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900"
            :class="{ 'border-red-500': first('email') }" />
          <p v-if="first('email')" class="text-red-600 text-xs mt-1 text-left">{{ first('email') }}</p>
        </label>

        <button type="submit" :disabled="loading" class="w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg transition
          disabled:cursor-not-allowed disabled:bg-primary-400">
          <Icon v-if="loading" name="tabler:loader-2" class="size-4 animate-spin text-white" />
          {{ loading ? "Mengirim Permintaan..." : "Kirim Kode Reset" }}
        </button>
      </form>
    </template>

    <template v-else>
      <form @submit.prevent="onSubmitOtp" class="space-y-5">
        <label class="block">
          <span class="text-gray-700 text-sm mb-2 block text-center">Kode OTP</span>

          <div class="flex justify-center gap-2 md:gap-3">
            <input v-for="(digit, index) in otpDigits" :key="index"
              :ref="(el) => otpInput(el as (HTMLInputElement | null), index)" v-model="otpDigits[index]" type="text"
              inputmode="numeric" pattern="[0-9]" maxlength="1" required @input="handleOtpInput(index, $event)"
              @keydown="handleOtpKeydown(index, $event)" class="size-10 md:size-12 text-xl font-bold text-center rounded-lg border border-gray-300 shadow-sm transition
              disabled:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-primary-500"
              :class="{ 'border-red-500 ring-red-200': has('otp') }" />
          </div>

          <p v-if="first('otp')" class="text-red-600 text-xs mt-2 block text-center">{{ first('otp') }}</p>
        </label>

        <div class="flex justify-end mb-3">
          <p class="text-sm text-gray-500">
            <span class="mr-1">Tidak menerima kode?</span>
            <button type="button" @click="onResendOtp"
              class="text-primary-600 hover:underline disabled:text-gray-400 disabled:no-underline font-medium"
              :disabled="loadingResend">
              Kirim Ulang OTP
              <Icon v-if="loadingResend" name="tabler:loader-2" class="size-3 ml-1 inline-block animate-spin" />
            </button>
          </p>
        </div>

        <button type="submit" :disabled="loading || isOtpIncomplete" class="w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg transition
          disabled:cursor-not-allowed disabled:bg-primary-400">
          <Icon v-if="loading" name="tabler:loader-2" class="size-4 animate-spin text-white" />
          {{ loading ? "Memverifikasi..." : "Verifikasi Kode" }}
        </button>
      </form>
    </template>

    <p class="mt-6 text-center text-gray-600 text-sm">
      Sudah ingat kata sandi Anda?
      <NuxtLink to="/auth/login" class="text-primary-600 hover:underline font-medium">
        Login sekarang
      </NuxtLink>
    </p>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: "auth" })

const route = useRoute()
const router = useRouter()
const toast = useToast()
const { setErrors, has, first } = useFormErrors()

/* ---------------- STATE ---------------- */
const page = ref<'forgot' | 'otp'>('forgot')
const loading = ref(false)
const loadingResend = ref(false)

/* ---------------- RID ---------------- */
const rid = ref<string | null>(
  typeof route.query.rid === 'string' ? route.query.rid : null
)

/* ---------------- FORGOT FORM ---------------- */
const formForgot = ref({
  email: ''
})

/* ---------------- OTP ---------------- */
const otpDigits = ref(['', '', '', '', '', ''])
const inputRefs = ref<(HTMLInputElement | null)[]>([])

const isOtpIncomplete = computed(() =>
  otpDigits.value.some(d => d.length !== 1)
)

const otpInput = (el: HTMLInputElement | null, index: number) => {
  inputRefs.value[index] = el
}

/* ---------------- OTP INPUT ---------------- */
const handleOtpInput = (index: number, e: Event) => {
  const el = e.target as HTMLInputElement
  const digit = el.value.replace(/\D/g, '').slice(0, 1)
  otpDigits.value[index] = digit

  if (digit && index < 5) {
    inputRefs.value[index + 1]?.focus()
  }

  setErrors({})
}

const handleOtpKeydown = (index: number, e: KeyboardEvent) => {
  if (e.key === 'Backspace' && !otpDigits.value[index] && index > 0) {
    inputRefs.value[index - 1]?.focus()
    otpDigits.value[index - 1] = ''
  }
}

/* ================= FORGOT ================= */
const onRequestForgotPassword = async () => {
  loading.value = true
  setErrors({})

  try {
    const res = await useApi(false).post(
      '/auth/password/forgot',
      formForgot.value
    )

    if (res.status) {
      rid.value = res.data.request_id
      page.value = 'otp' // LANGSUNG UBAH STATE LOKAL
      toast.success(res.message || 'Kode OTP dikirim')

      nextTick(() => inputRefs.value[0]?.focus()) // Fokuskan input OTP pertama

      return // Akhiri fungsi
    }

  } catch (err: any) {
    if (err?.data?.errors) setErrors(err.data.errors)
    else toast.error(err?.data?.message || 'Gagal mengirim OTP')
  } finally {
    loading.value = false
  }
}

/* ================= OTP ================= */
const onSubmitOtp = async () => {
  if (!rid.value || isOtpIncomplete.value) {
    setErrors({ otp: ['Kode OTP tidak lengkap'] })
    return
  }

  loading.value = true
  setErrors({})

  try {
    const res = await useApi(false).post(
      '/auth/password/validate-otp',
      {
        request_id: rid.value,
        otp: otpDigits.value.join('')
      }
    )

    if (res.status) {
      toast.success(res.message || 'OTP valid')

      await navigateTo({ // TAMBAHKAN 'await'
        path: '/auth/password/reset',
        query: { rid: res.data.request_id }
      })

      return
    }

  } catch (err: any) {
    setErrors({
      otp: [err?.data?.message || 'OTP tidak valid']
    })
  } finally {
    loading.value = false
  }
}

/* ================= RESEND ================= */
const onResendOtp = async () => {
  if (!rid.value) return

  loadingResend.value = true
  otpDigits.value = ['', '', '', '', '', '']

  try {
    await useApi(false).post('/auth/password/resend-otp', {
      rid: rid.value
    })

    toast.info('OTP baru telah dikirim')
  } catch {
    toast.error('Gagal mengirim ulang OTP')
  } finally {
    loadingResend.value = false
  }
}

/* ================= ROUTE WATCH ================= */
watch(
  () => route.query.rid,
  (v) => {
    if (typeof v === 'string') {
      rid.value = v
      page.value = 'otp'
      nextTick(() => inputRefs.value[0]?.focus())
    } else {
      page.value = 'forgot'
    }
  },
  { immediate: true }
)
</script>