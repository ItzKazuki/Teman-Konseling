<template>
  <div class="space-y-8 pb-10">
    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div class="space-y-2">
      <h2 class="text-2xl font-bold text-gray-800">Ubah Password</h2>
      <p class="text-sm text-gray-500">Demi keamanan akun Anda, jangan berikan password kepada siapapun.</p>
    </div>

    <form @submit.prevent="onUpdatePassword" class="space-y-6">
      <div class="space-y-4">
        <div>
          <label for="current_password" class="form-label-custom">Password Saat Ini</label>
          <div class="relative">
            <input 
              id="current_password" 
              v-model="form.current_password" 
              :type="showOld ? 'text' : 'password'" 
              class="form-input-custom pr-10" 
              placeholder="Masukkan password lama"
              required 
            />
            <button type="button" @click="showOld = !showOld" class="absolute right-3 top-3 text-gray-400">
              <Icon :name="showOld ? 'mdi:eye-off' : 'mdi:eye'" size="20" />
            </button>
          </div>
        </div>

        <hr class="border-gray-100 my-2" />

        <div>
          <label for="password" class="form-label-custom">Password Baru</label>
          <div class="relative">
            <input 
              id="password" 
              v-model="form.password" 
              :type="showNew ? 'text' : 'password'" 
              class="form-input-custom pr-10" 
              placeholder="Minimal 8 karakter"
              required 
            />
            <button type="button" @click="showNew = !showNew" class="absolute right-3 top-3 text-gray-400">
              <Icon :name="showNew ? 'mdi:eye-off' : 'mdi:eye'" size="20" />
            </button>
          </div>
        </div>

        <div>
          <label for="confirm_password" class="form-label-custom">Konfirmasi Password Baru</label>
          <div class="relative">
            <input 
              id="confirm_password" 
              v-model="form.password_confirmation" 
              :type="showConfirm ? 'text' : 'password'" 
              class="form-input-custom pr-10" 
              placeholder="Ulangi password baru"
              required 
            />
            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-3 text-gray-400">
              <Icon :name="showConfirm ? 'mdi:eye-off' : 'mdi:eye'" size="20" />
            </button>
          </div>
          <p v-if="isNotMatch" class="text-xs text-red-500 mt-1 italic">
            Konfirmasi password tidak cocok.
          </p>
        </div>
      </div>

      <div class="pt-4">
        <button 
          type="submit" 
          :disabled="isSubmitting || isNotMatch" 
          class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold shadow-md shadow-primary-500/30 
                 hover:bg-primary-700 focus:ring-2 focus:ring-primary-400 transition duration-200 disabled:bg-gray-400"
        >
          <Icon v-if="isSubmitting" name="svg-spinners:ring-resize" class="mr-2" />
          {{ isSubmitting ? 'Memproses...' : 'Simpan Password Baru' }}
        </button>
        
        <button 
          type="button" 
          @click="navigateTo('/profile/edit')"
          class="w-full mt-3 py-3 text-gray-500 font-medium hover:text-gray-700 transition"
        >
          Batal
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
const isSubmitting = ref(false);
const showOld = ref(false);
const showNew = ref(false);
const showConfirm = ref(false);

const form = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
});

// Validasi apakah password cocok
const isNotMatch = computed(() => {
  return form.password_confirmation !== '' && form.password !== form.password_confirmation;
});

async function onUpdatePassword() {
  if (isNotMatch.value) return;

  isSubmitting.value = true;
  try {
    const res = await useApi().put('/student/profile/password/update', {
      current_password: form.current_password,
      password: form.password,
      password_confirmation: form.password_confirmation
    });

    if (res.status) {
      useToast().success('Password berhasil diperbarui');
      navigateTo('/profile/edit');
    }
  } catch (error) {
    useToast().error(error.data?.message || 'Gagal mengubah password. Pastikan password lama benar.');
  } finally {
    isSubmitting.value = false;
  }
}
</script>