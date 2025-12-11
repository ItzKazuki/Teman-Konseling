<template>
  <div class="space-y-8">
    <AppBackButton />

    <div class="flex items-center space-x-4 pb-2 border-b border-gray-100">
      <Icon name="tk:chat-bold" class="w-7 h-7 text-primary-600" aria-hidden="true" />
      <h1 class="text-2xl font-bold text-gray-900">Mulai Konseling Chat</h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 space-y-4">
      <h2 class="text-xl font-extrabold text-gray-800 flex items-center gap-2">
        <Icon name="tk:profile-bold" class="w-5 h-5" />
        Konselor BK Anda
      </h2>

      <div class="flex items-center space-x-4">
        <img :src="counselor.photoUrl" alt="Foto Konselor"
          class="w-16 h-16 object-cover rounded-full border-2 border-primary-100 shadow-sm" />

        <div>
          <p class="text-lg font-semibold text-gray-900">{{ counselor.name }}</p>
          <p class="text-sm text-gray-600">Guru Bimbingan Konseling (BK)</p>
          <p class="text-sm font-medium text-primary-600 mt-1">{{ counselor.availability }}</p>
        </div>
      </div>
    </div>

    <div class="space-y-4">
      <h2 class="text-xl font-bold text-gray-800">Langkah Sebelum Chat</h2>

      <ol class="list-decimal list-inside text-gray-700 space-y-3 pl-4">
        <li>Pastikan Anda sudah siap berbagi mengenai perasaan atau masalah Anda.</li>
        <li>Waktu respon konselor mungkin bervariasi. Mohon bersabar menunggu balasan.</li>
        <li>Chat akan dialihkan ke aplikasi WhatsApp Anda. Pastikan WhatsApp sudah terinstal.</li>
        <li>Sesi ini bersifat rahasia dan profesional.</li>
      </ol>
    </div>

    <div class="fixed inset-x-0 bottom-0 p-4 bg-white border-t border-gray-200 
                sm:static sm:p-0 sm:border-none sm:max-w-md sm:mx-auto">
      <button @click="openWhatsAppChat" class="w-full py-4 bg-green-500 text-white rounded-xl font-bold text-lg 
               hover:bg-green-600 transition duration-200 shadow-lg shadow-green-500/40 
               flex items-center justify-center gap-3">
        <Icon name="logos:whatsapp-icon" class="w-6 h-6" />
        Mulai Chat Sekarang
      </button>
    </div>

  </div>
</template>

<script setup>
// 1. Data Konselor (Ganti dengan data dari API/State Management)
const counselor = reactive({
  name: "Bpk. Dwi Prasetyo, M.Pd",
  phoneNumber: "6281234567890", // Ganti dengan nomor WhatsApp Guru BK (tanpa + atau 0 di depan)
  photoUrl: "/static/images/profile.png", // Ganti dengan path foto yang benar
  initialMessage: "Halo Bapak/Ibu, saya [Nama Anda] dari [Kelas/Sekolah Anda]. Saya ingin memulai sesi konseling terkait [Sebutkan Topik Singkat].",
  availability: "Aktif: Senin - Jumat (09:00 - 15:00)"
});

// 2. Fungsi untuk Membuka WhatsApp
function openWhatsAppChat() {
  const encodedMessage = encodeURIComponent(counselor.initialMessage);

  // Format URL WhatsApp (wa.me)
  const waUrl = `https://wa.me/${counselor.phoneNumber}?text=${encodedMessage}`;

  // Membuka tab baru atau aplikasi WhatsApp
  window.open(waUrl, '_blank');

  console.log("Mengarahkan ke: " + waUrl);
}

// 3. Tambahkan fungsi untuk NuxtLink atau event (jika Anda ingin navigasi kembali setelah chat)
function goBack() {
  return navigateTo('/home')
}
</script>