<template>
  <div class="flex justify-between items-center pb-2 border-b border-gray-100">
    <AppBackButton />
    <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
  </div>

  <article v-if="article" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="space-y-4">
      <img :src="article.imageUrl" :alt="article.title"
        class="w-full h-auto object-cover rounded-xl shadow-md max-h-96" />

      <span class="inline-block text-sm font-semibold py-1 px-4 rounded-full text-secondary-700 bg-secondary-100">
        {{ article.topic }}
      </span>
    </div>

    <header class="space-y-2 pb-4 border-b border-gray-200">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
        {{ article.title }}
      </h1>

      <div class="flex items-center space-x-3 text-sm text-gray-600">
        <Icon name="tabler:user" class="w-4 h-4 text-primary-600" />
        <p class="font-medium">{{ article.author }}</p>
        <span class="text-gray-400">•</span>
        <Icon name="tabler:calendar" class="w-4 h-4 text-primary-600" />
        <time :datetime="article.date">{{ article.date }}</time>
      </div>
    </header>

    <section class="prose max-w-none prose-lg text-gray-800 space-y-6">
      <div v-html="article.content"></div>
    </section>

    <footer class="pt-8 border-t border-gray-200 text-center">
      <h3 class="text-xl font-bold text-gray-800 mb-4">Butuh Bantuan Lebih Lanjut?</h3>
      <NuxtLink to="/counselors" class="inline-flex items-center justify-center py-3 px-6 bg-primary-600 text-white rounded-lg font-semibold 
                 hover:bg-primary-700 transition duration-200 shadow-md">
        Mulai Konseling dengan Guru BK
      </NuxtLink>
    </footer>

  </article>

  <div v-else class="text-center py-20">
    <h1 class="text-3xl font-bold text-gray-900">Artikel Tidak Ditemukan</h1>
    <p class="text-gray-600 mt-2">Maaf, artikel yang Anda cari mungkin telah dihapus atau URL salah.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const article = ref(null);

// 1. Dummy Data Repository (Harusnya dari API)
const articlesData = [
  {
    id: 1,
    title: "Mengelola Kecemasan Belajar di Masa Ujian",
    author: "Bpk. Dwi Prasetyo, M.Pd",
    topic: "Kecemasan & Stres",
    date: "12 Des 2025",
    imageUrl: "/static/images/kesmen.png",
    slug: "mengelola-kecemasan-ujian",
    content: `
      <p>Kecemasan saat menghadapi ujian adalah hal yang wajar. Namun, jika kecemasan itu mulai mengganggu fokus belajar dan kesehatan mental Anda, itu perlu diatasi. Penting untuk diingat bahwa nilai ujian tidak mendefinisikan seluruh potensi diri Anda.</p>
      
      <h2>Strategi Mengatasi Kecemasan</h2>
      
      <h3>1. Atur Pernapasan</h3>
      <p>Latihan pernapasan dalam (4-7-8) dapat menenangkan sistem saraf Anda dengan cepat. Tarik napas 4 detik, tahan 7 detik, hembuskan 8 detik. Ulangi 3-4 kali sebelum belajar atau tidur.</p>
      
      <h3>2. Belajar Berpikir Realistis</h3>
      <p>Seringkali kecemasan dipicu oleh pemikiran yang hiperbolis (misal: "Jika saya gagal, hidup saya hancur"). Tantang pemikiran ini! Tanyakan pada diri sendiri, "Apa skenario terburuk yang realistis?" dan "Bagaimana saya akan mengatasinya?".</p>
      
      <h3>3. Jangan Belajar Sambil Begadang</h3>
      <p>Tidur adalah kunci konsolidasi memori. Begadang justru akan menurunkan kemampuan otak Anda untuk mengingat informasi dan meningkatkan tingkat stres.</p>
      
      <p>Jika kecemasan Anda terasa tidak tertahankan, jangan ragu untuk mencari bantuan. Guru BK Anda siap mendengarkan tanpa menghakimi.</p>
    `, // Konten dummy
  },
  // ... artikel lainnya di sini
];

// 2. Fungsi untuk Mengambil Artikel berdasarkan Slug
const fetchArticle = (slug) => {
  return articlesData.find(a => a.slug === slug);
};

onMounted(() => {
  const slug = route.params.slug;
  const fetchedArticle = fetchArticle(slug);

  if (fetchedArticle) {
    article.value = fetchedArticle;
    // Set judul halaman
    useHead({
      title: fetchedArticle.title,
    });
  }
});
</script>

<style scoped>
/*
  Pastikan Tailwind CSS Anda memiliki konfigurasi typography (plugin @tailwindcss/typography) 
  agar elemen HTML dasar seperti H2, P, UL, dan LI yang dimasukkan melalui v-html 
  dapat ditampilkan dengan gaya yang bagus dan mudah dibaca.
*/
.prose {
  /* Contoh override dasar jika diperlukan */
  font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
}
</style>