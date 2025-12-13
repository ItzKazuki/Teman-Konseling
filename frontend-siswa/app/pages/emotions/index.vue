<template>
  <div class="space-y-8">
    <AppHeader />

    <div class="space-y-2">
      <h1 class="text-2xl font-bold text-gray-900">Apa Emosi yang Kamu Rasakan?</h1>
      <p class="text-gray-600">Pilih satu emosi untuk melihat detail dan penjelasan singkat.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
      <div v-for="(emotion, index) in emotions" :key="index" :class="[
        'aspect-square flex flex-col items-center justify-center rounded-xl p-3 cursor-pointer transition-all duration-200',
        // Default styling dan hover
        'border border-gray-200 hover:border-primary-500 hover:shadow-md',
        // Active state styling
        { 'bg-primary-50 border-primary-600 ring-2 ring-primary-200 shadow-lg': selectedEmotion && selectedEmotion.title === emotion.title }
      ]" @click="selectEmotion(emotion)">

        <Icon :name="`tk:emotion-${emotion.iconName}`"
          class="w-3/5 h-3/5 object-contain mb-2 transition-transform duration-200" :class="[
            selectedEmotion && selectedEmotion.title === emotion.title ? 'text-primary-600 scale-110' : 'text-gray-500',
          ]" aria-hidden="true" />

        <p class="text-xs sm:text-sm font-semibold text-center transition-all duration-200"
          :class="{ 'text-primary-700 font-extrabold': selectedEmotion && selectedEmotion.title === emotion.title }">
          {{ emotion.title }}
        </p>
      </div>
    </div>

    <EmotionDetailSheet :emotion="selectedEmotion" :show="!!selectedEmotion" @close="clearEmotion" />

  </div>
</template>

<script setup>
const emotions = [
  { title: 'Senang', iconName: 'happy', description: 'Perasaan gembira dan puas yang timbul dari hal positif atau pencapaian. Biasanya ditandai dengan energi yang meningkat dan senyum.' },
  { title: 'Sedih', iconName: 'sad', description: 'Respons alami terhadap kehilangan, kekecewaan, atau kesendirian. Penting untuk mengakui dan memproses perasaan ini.' },
  { title: 'Marah', iconName: 'angry', description: 'Reaksi kuat terhadap ketidakadilan, frustrasi, atau ancaman. Penting untuk mengelola kemarahan sebelum mengambil tindakan.' },
  { title: 'Takut', iconName: 'nervous', description: 'Emosi yang dipicu oleh ancaman bahaya, baik nyata maupun dibayangkan. Ini adalah mekanisme pertahanan alami.' },
  { title: 'Bangga', iconName: 'proud', description: 'Perasaan puas yang mendalam terhadap diri sendiri atau orang lain karena pencapaian atau tindakan yang baik.' },
  { title: 'Biasa Aja', iconName: 'biasa-aja', description: 'Keadaan netral, tidak terlalu gembira, tidak terlalu sedih. Ini bisa menjadi tanda stabilitas atau kelelahan mental.' },
];

const selectedEmotion = ref(null);

function selectEmotion(emotion) {
  if (selectedEmotion.value && selectedEmotion.value.title === emotion.title) {
    clearEmotion();
  } else {
    selectedEmotion.value = emotion;
  }
}

function clearEmotion() {
  selectedEmotion.value = null;
}
</script>