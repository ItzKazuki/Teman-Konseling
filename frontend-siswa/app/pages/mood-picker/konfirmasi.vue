<template>
  <div class="min-h-full flex flex-col space-y-8">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo" class="h-6 w-auto" />
    </div>

    <div class="flex-1 flex flex-col items-center justify-center text-center space-y-6 py-10 animate-fade-in">

      <div class="relative">
        <div class="absolute inset-0 bg-primary-100 rounded-full blur-3xl opacity-30 scale-75"></div>
        <img src="/static/guru.svg" alt="Ilustrasi Guru" class="relative mx-auto w-3/4 max-w-70 drop-shadow-md" />
      </div>

      <div class="space-y-3 px-4">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
          Terima kasih, <span class="text-primary-600">{{ auth.user?.name }}</span>!
        </h1>
        <p class="text-gray-600 max-w-[320px] mx-auto leading-relaxed">
          Terima kasih karena telah terbuka hari ini. Kami tunggu emosi kamu esok hari! <span
            class="font-bold text-primary-600">Kamu hebat!</span>
        </p>
      </div>

    </div>

    <div class="pt-6">
      <NuxtLink to="/home"
        class="flex items-center justify-center w-full py-4 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-200 hover:bg-primary-700 hover:shadow-none focus:ring-2 focus:ring-primary-400 active:scale-[0.98] transition-all">
        Lanjutkan ke Beranda
        <Icon name="tabler:arrow-right" class="ml-2 w-5 h-5" />
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'custom'
});

const auth = useAuthStore();
</script>