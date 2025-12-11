<template>
  <div class="space-y-8">
    
    <AppHeader size="xl" />

    <!-- <div class="space-y-2 pt-2">
      <h1 class="text-2xl font-bold text-gray-800">Halo, Chaeza!</h1>
      <p class="text-lg text-gray-600">Apa kabar kamu hari ini? Ada yang ingin kamu sampaikan?</p>
    </div>
    
    <div class="flex flex-col gap-4">
      <NuxtLink to="/mood-picker"
        class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold text-center shadow-md hover:bg-primary-700 transition duration-200">
        Pilih Emosi Saya Sekarang
      </NuxtLink>
    </div> -->

    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
      <h2 class="text-sm font-semibold text-yellow-700 mb-2">✨ Motivasi Hari Ini</h2>
      <p class="italic text-yellow-800 text-base">
        "Kekuatan terbesar kita adalah keberanian untuk bangkit setelah kita jatuh."
      </p>
      <p class="text-right text-xs text-yellow-600 mt-2">— Anonymous</p>
    </div>

    <!-- <div class="pt-4">
      <h2 class="text-xl font-bold mb-4 text-gray-800">Butuh Bantuan Lebih?</h2>
      <NuxtLink to="/contact-counselor"
        class="flex items-center justify-center gap-2 w-full py-3 border border-primary-600 text-primary-600 rounded-xl font-semibold text-center hover:bg-primary-50 transition duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4" fill="currentColor">
          <path
            d="M164.9 24.6c-5.8-9.1-17.5-12.7-27.4-8.8L37.1 53.3c-13.8 5.3-23.7 18.5-23.7 33.1L0 426.7C0 470.2 35.8 505.1 79.3 505.1H299c55.3 0 100-44.7 100-100V100C399 44.7 354.3 0 299 0H164.9zM224 256c-44.2 0-80-35.8-80-80s35.8-80 80-80s80 35.8 80 80s-35.8 80-80 80z" />
        </svg>
        Kontak Guru / Konselor
      </NuxtLink>
    </div> -->

    <div class="pt-4">
      <h2 class="text-xl font-bold mb-4 text-gray-800">Bacaan untukmu Hari Ini</h2>
      
      <div class="space-y-4">
        <div class="flex gap-4 p-3 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition cursor-pointer">
          <img src="https://via.placeholder.com/80x80?text=Artikel+1" alt="Thumbnail Artikel" class="w-20 h-20 object-cover rounded-md shrink-0">
          <div>
            <p class="text-xs font-semibold text-primary-600 mb-1">Kesehatan Mental</p>
            <h3 class="text-sm font-bold text-gray-800 line-clamp-2">5 Cara Efektif Mengelola Rasa Cemas Saat Ujian</h3>
            <p class="text-xs text-gray-500 mt-1">oleh Dr. Rina, 5 menit baca</p>
          </div>
        </div>
        
        <div class="flex gap-4 p-3 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition cursor-pointer">
          <img src="https://via.placeholder.com/80x80?text=Artikel+2" alt="Thumbnail Artikel" class="w-20 h-20 object-cover rounded-md shrink-0">
          <div>
            <p class="text-xs font-semibold text-primary-600 mb-1">Kesejahteraan Diri</p>
            <h3 class="text-sm font-bold text-gray-800 line-clamp-2">Pentingnya Batasan Digital untuk Pelajar</h3>
            <p class="text-xs text-gray-500 mt-1">oleh Tim Teman Konseling, 7 menit baca</p>
          </div>
        </div>
        
        <div class="text-center pt-2">
          <NuxtLink to="/articles" class="text-sm font-medium text-primary-600 hover:text-primary-700">
            Lihat Semua Artikel &rarr;
          </NuxtLink>
        </div>
      </div>
    </div>
    
    </div>
</template>