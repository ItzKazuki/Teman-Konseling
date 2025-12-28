<template>
  <div class="space-y-8 pb-10">
    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div class="flex items-center flex-col gap-4">
      <div class="relative w-28 h-28">
        <img :src="avatarPreview || auth.user?.avatar_url || '/static/images/profile.png'" 
             alt="Profile" 
             class="w-full h-full object-cover rounded-full border-4 border-white shadow-md ring-2 ring-primary-500/50" />

        <label for="avatar-input"
          class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center border-2 border-white text-white hover:bg-primary-700 transition cursor-pointer"
          title="Ubah Foto Profil">
          <Icon name="mdi:pencil" />
          <input id="avatar-input" type="file" class="hidden" accept="image/*" @change="handleFileChange" />
        </label>
      </div>
      <h2 class="text-xl font-bold text-gray-800">Ubah Data Profil</h2>
    </div>

    <form @submit.prevent="onSubmit" class="space-y-6">
      <div class="space-y-4">
        <h1 class="font-bold text-lg text-gray-800 border-b pb-2">Detail Personal</h1>

        <div class="space-y-4">
          <div>
            <label for="name" class="form-label-custom">Nama Lengkap</label>
            <input id="name" v-model="form.name" type="text" class="form-input-custom" placeholder="Nama Lengkap Anda" required />
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="email" class="form-label-custom">Email</label>
              <input id="email" v-model="form.email" type="email" class="form-input-custom" placeholder="Email Anda" required />
            </div>
            <div>
              <label for="phone" class="form-label-custom">Nomor Telepon</label>
              <input id="phone" v-model="form.phone" type="text" class="form-input-custom" placeholder="0812..." required />
            </div>
          </div>

          <div>
            <label for="password" class="form-label-custom">Password</label>
            <div class="flex items-center border border-gray-300 p-3 rounded-lg w-full focus-within:border-primary-600 focus-within:ring-1 focus-within:ring-primary-600 transition">
              <input id="password" type="password" class="bg-transparent outline-none w-full" placeholder="•••••••••••" readonly />
              <p class="text-primary-600 text-sm font-semibold cursor-pointer ml-3 whitespace-nowrap" @click.prevent="onChangePassword">
                Ubah Password
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-4 pt-4">
        <div>
          <h1 class="font-bold text-lg text-gray-800 border-b pb-2">Detail Alamat</h1>
          <p class="text-sm text-gray-500 mt-2">Mengapa kami membutuhkan alamat Anda? 
            <NuxtLink to="/faq-address" class="text-primary-600 underline hover:text-primary-700">Lihat di sini</NuxtLink>
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="form-label-custom">Kode Pos</label>
            <input v-model="form.postal_code" type="text" class="form-input-custom" placeholder="Kode Pos" />
          </div>
          <div>
            <label class="form-label-custom">Alamat Lengkap</label>
            <input v-model="form.address" type="text" class="form-input-custom" placeholder="Jalan, Blok, No" />
          </div>
          <div>
            <label class="form-label-custom">Kelurahan / Desa</label>
            <input v-model="form.village" type="text" class="form-input-custom" placeholder="Kelurahan" />
          </div>
          <div>
            <label class="form-label-custom">Kecamatan</label>
            <input v-model="form.district" type="text" class="form-input-custom" placeholder="Kecamatan" />
          </div>
          <div>
            <label class="form-label-custom">Kota / Kabupaten</label>
            <input v-model="form.city" type="text" class="form-input-custom" placeholder="Kota" />
          </div>
          <div>
            <label class="form-label-custom">Provinsi</label>
            <input v-model="form.province" type="text" class="form-input-custom" placeholder="Provinsi" />
          </div>
        </div>
      </div>
      
      <button type="submit" :disabled="isSubmitting"
        class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold shadow-md shadow-primary-500/30 
               hover:bg-primary-700 focus:ring-2 focus:ring-primary-400 transition duration-200 disabled:bg-gray-400">
        <Icon v-if="isSubmitting" name="svg-spinners:ring-resize" class="mr-2" />
        {{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
      </button>
    </form>

    <ModalCropper :show="showCropper" :src="imageSource" :aspect-ratio="1" @close="showCropper = false" @cropped="onCropped" />
  </div>
</template>

<script setup>
const auth = useAuthStore();
const isSubmitting = ref(false);

// State untuk file gambar
const avatarFile = ref(null);
const avatarPreview = ref(null);
const imageSource = ref(null);
const showCropper = ref(false);

// Reactive Form sesuai skema User
const form = reactive({
  name: auth.user?.name || '',
  email: auth.user?.email || '',
  phone: auth.user?.phone || '',
  postal_code: auth.user?.postal_code || '',
  address: auth.user?.address || '',
  village: auth.user?.village || '',
  district: auth.user?.district || '',
  city: auth.user?.city || '',
  province: auth.user?.province || '',
  // Data pendukung yang tidak diubah di form ini tapi perlu untuk integritas data jika diperlukan
  parent_name: auth.user?.parent_name || '',
  parent_phone_number: auth.user?.parent_phone_number || '',
});

// Handle input file
function handleFileChange(event) {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    imageSource.value = reader.result;
    showCropper.value = true;
  };
  reader.readAsDataURL(file);
}

// Handle hasil crop
function onCropped(base64) {
  avatarPreview.value = base64;
  // Ubah base64 ke File Object (Gunakan helper base64ToFile yang sudah kita buat sebelumnya)
  avatarFile.value = base64ToFile(base64, "avatar_siswa.png");
  showCropper.value = false;
}

async function onSubmit() {
  isSubmitting.value = true;
  
  try {
    // Gunakan FormData karena ada file upload
    const formData = new FormData();
    
    // Append data teks
    Object.keys(form).forEach(key => {
      formData.append(key, form[key] || '');
    });

    // Append data file jika ada perubahan foto
    if (avatarFile.value) {
      formData.append('avatar', avatarFile.value);
    }

    // Gunakan useApi atau fetch langsung
    // Catatan: Laravel/PHP memerlukan method spoofing _method: 'PUT' jika menggunakan FormData
    formData.append('_method', 'PUT');

    const res = await useApi().post('/student/profile/update', formData);

    if (res.status) {
      useToast().success('Profil berhasil diperbarui');
      auth.setUser(res.data);

      // Redirect ke halaman profil
      navigateTo('/profile');
    }
  } catch (error) {
    useToast().error(error.data?.message || 'Gagal memperbarui profil');
  } finally {
    isSubmitting.value = false;
  }
}

function onChangePassword() {
  navigateTo('/profile/change-password');
}
</script>