<template>
  <div class="min-h-screen flex flex-col justify-between">
    
    <div class="flex justify-between items-center mb-10">
      <AppBackButton /> 
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="flex flex-col items-center justify-center text-center space-y-8 grow">
      
      <img src="/static/guru.svg" alt="Ilustrasi Guru atau Konselor" class="mx-auto w-3/4 max-w-xs" />

      <div class="space-y-3">
        <h1 class="text-3xl font-extrabold text-primary-600">Terima kasih, Chaeza!</h1>
        <p class="text-gray-600 max-w-sm mx-auto">Terima kasih karena telah terbuka hari ini. Kami tunggu emosi kamu esok hari! Kamu hebat!</p>
      </div>

    </div>
    
    <div class="flex flex-col gap-4 text-center mt-10">
      <NuxtLink to="/home"
        class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 focus:ring-2 focus:ring-primary-400">
        Lanjutkan ke Beranda
      </NuxtLink>
    </div>
  </div>
</template>