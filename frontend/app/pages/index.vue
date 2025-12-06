<template>
  <div class="flex flex-col justify-center items-center p-8 gap-4">
    <h1 class="font-bold text-6xl">Halo dunia</h1>
    
    <button class="btn-primary btn-lg"
      @click="() => toast.info('ini adalah toast dengan teks yang sangat panjang wakalu ajsdnajsdba aksjdnakjbdjasnjdk akjsbdkas da kjabdjkdajkdnaj')">Tekan
      Untuk Melihat toast</button>
    <button class="btn-primary btn-lg" @click="() => alert.error('Berhasil mendapatkan sesuatu')">Tekan Untuk Melihat
      Alert</button>
  </div>
</template>

<script setup lang="ts">
const toast = useToast();
const alert = useAlert();
</script>