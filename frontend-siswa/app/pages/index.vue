<template>
  <div class="w-full max-w-sm flex flex-col items-center text-center space-y-10 p-6 sm:p-8 md:p-10">

    <img src="/static/login-illustrator.svg" alt="Login Ilustrator" class="w-2/3 max-w-72 mx-auto mb-4" />

    <div class="space-y-2">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Jelajahi Aplikasinya</h1>
      <p class="text-base font-normal text-gray-600">Eksplorasi dan pahami emosi kamu lewat aplikasi Teman Konseling.
      </p>
    </div>

    <div class="flex flex-col gap-4 w-full">

      <NuxtLink to="/auth/login"
        class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/50 hover:bg-primary-700 transition duration-200 focus:ring-2 focus:ring-primary-400">
        Login
      </NuxtLink>

      <!-- <NuxtLink to="/auth/register"
        class="w-full py-4 rounded-xl border border-gray-300 text-gray-800 font-semibold hover:border-gray-500 hover:bg-gray-50 transition duration-200">
        Buat Akun Baru
      </NuxtLink> -->
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'auth'
})
</script>