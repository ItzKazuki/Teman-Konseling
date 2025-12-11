<template>
  <div class="space-y-8">

    <div class="flex justify-between items-center">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Ilustrator" class="h-6 w-auto" />
    </div>

    <div class="space-y-2">
      <h1 class="text-2xl sm:text-3xl font-bold">Pilih Emosi Anda</h1>
      <p class="text-gray-500 text-base">Emosi apa yang sedang kamu rasakan saat ini?</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="(emotion, index) in emotions" :key="index" :class="[
        'aspect-square flex flex-col items-center justify-center rounded-xl p-4 cursor-pointer transition-all duration-200',
        'border border-gray-200 hover:border-primary-500 hover:shadow-md',
        { 'bg-primary-50 border-primary-600 shadow-lg': selectedEmotion === emotion.title }
      ]" @click="selectEmotion(emotion.title)">

        <Icon :name="`tk:emotion-${emotion.iconName}`" :class="{ 'scale-110': selectedEmotion === emotion.title }"
          class="w-1/2 h-1/2 object-contain mb-3 transition-transform duration-200" aria-hidden="true" />

        <p class="text-sm font-semibold text-center transition-all duration-200"
          :class="{ 'text-primary-600 font-extrabold': selectedEmotion === emotion.title }">
          {{ emotion.title }}
        </p>
      </div>
    </div>

    <div class="flex flex-col gap-3 pt-4">
      <NuxtLink :to="selectedEmotion ? '/mood-picker/konfirmasi' : '#'" :class="[
        'w-full py-4 rounded-xl font-semibold transition-colors duration-200 text-center',
        selectedEmotion
          ? 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400'
          : 'bg-gray-200 text-gray-500 cursor-not-allowed'
      ]">
        Ini Emosi Saya ({{ selectedEmotion || 'Pilih dulu' }})
      </NuxtLink>

      <NuxtLink to="/custom-emotion" class="text-center text-sm font-medium text-gray-600 hover:text-black underline">
        Pilihan saya tidak ada di sini
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'; // Import ref untuk state

// State untuk menyimpan emosi yang dipilih
const selectedEmotion = ref<string | null>(null);

const emotions = [
  {
    title: 'Senang',
    iconName: 'happy'
  },
  {
    title: 'Sedih',
    iconName: 'sad'
  },
  {
    title: 'Marah',
    iconName: 'angry'
  },
  {
    title: 'Takut',
    iconName: 'nervous'
  },
  {
    title: 'Bangga',
    iconName: 'proud'
  },
  {
    title: 'Biasa Aja',
    iconName: 'biasa-aja'
  },
]

// Fungsi untuk memilih emosi
function selectEmotion(emotionTitle: string) {
  // Toggle pilihan: jika emosi yang sama diklik lagi, batalkan pilihan
  if (selectedEmotion.value === emotionTitle) {
    selectedEmotion.value = null;
  } else {
    selectedEmotion.value = emotionTitle;
  }
}
</script>