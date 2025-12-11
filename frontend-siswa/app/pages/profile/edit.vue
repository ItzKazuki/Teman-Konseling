<template>
  <div class="space-y-8">
    
    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div class="flex items-center flex-col gap-4">
      <div class="relative w-28 h-28">
        <img src="/static/images/profile.png" alt="Profile" 
             class="w-full h-full object-cover rounded-full border-4 border-white shadow-md ring-2 ring-primary-500/50" />

        <button
          class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center border-2 border-white text-white hover:bg-primary-700 transition"
          @click.prevent="onEditPhoto"
          title="Ubah Foto Profil">
          <Icon name="mdi:pencil" />
        </button>
      </div>
      <h2 class="text-xl font-bold text-gray-800">Ubah Data Profil</h2>
    </div>

    <form @submit.prevent="onSubmit" class="space-y-6">
      
      <div class="space-y-4">
        <h1 class="font-bold text-lg text-gray-800 border-b pb-2">Detail Personal</h1>

        <div class="space-y-4">
          <div>
            <label for="name" class="font-medium text-xs block mb-1 text-gray-700">Nama Lengkap</label>
            <input id="name" type="text" class="border border-gray-300 p-3 rounded-lg w-full focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition"
              placeholder="Nama Lengkap Anda" value="Chaeza Ibnu Akbar" />
          </div>
          
          <div>
            <label for="email" class="font-medium text-xs block mb-1 text-gray-700">Email</label>
            <input id="email" type="email" class="border border-gray-300 p-3 rounded-lg w-full focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition"
              placeholder="Email Anda" value="chaezaibnuakbar@gmail.com" />
          </div>
          
          <div>
            <label for="password" class="font-medium text-xs block mb-1 text-gray-700">Password</label>
            <div class="flex items-center border border-gray-300 p-3 rounded-lg w-full focus-within:border-primary-600 focus-within:ring-1 focus-within:ring-primary-600 transition">
              <input id="password" type="password"
                class="bg-transparent outline-none w-full" placeholder="•••••••••••" />
              <p class="text-primary-600 text-sm cursor-pointer ml-3" @click.prevent="onChangePassword">
                Ubah
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

        <div class="space-y-4">
          <div v-for="field in addressFields" :key="field.id">
            <label :for="field.id" class="font-medium text-xs block mb-1 text-gray-700">{{ field.label }}</label>
            <input :id="field.id" :type="field.type"
              class="border border-gray-300 p-3 rounded-lg w-full focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition"
              :placeholder="field.placeholder" :value="field.value" />
          </div>
        </div>
      </div>
      
      <button type="submit"
        class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold shadow-md shadow-primary-500/30 
               hover:bg-primary-700 focus:ring-2 focus:ring-primary-400 transition duration-200">
        Simpan Perubahan
      </button>
    </form>
  </div>
</template>

<script setup>
// Data dummy untuk Address (sebaiknya ini dipindahkan ke dalam state atau di-fetch)
const addressFields = [
  { id: 'poscode', label: 'Kode Pos', type: 'text', placeholder: 'Kode pos', value: '' },
  { id: 'address', label: 'Alamat Lengkap', type: 'text', placeholder: 'Jalan, Blok, Nomor', value: '' },
  { id: 'village', label: 'Kelurahan / Desa', type: 'text', placeholder: 'Kelurahan atau desa', value: '' },
  { id: 'district', label: 'Kecamatan', type: 'text', placeholder: 'Kecamatan', value: '' },
  { id: 'city', label: 'Kota / Kabupaten', type: 'text', placeholder: 'Kota atau kabupaten', value: '' },
  { id: 'province', label: 'Provinsi', type: 'text', placeholder: 'Provinsi', value: '' },
];

function onSubmit() {
  console.log('Form disubmit: Simpan Perubahan')
  // return navigateTo('/profile') // Contoh redirect
}

function onEditPhoto() {
  console.log('Tombol edit foto ditekan: Buka file picker')
}

function onChangePassword() {
  console.log('Tombol Ubah Password ditekan: Buka modal atau halaman baru')
  // Contoh: navigateTo('/profile/change-password')
}
</script>