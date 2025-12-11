<template>
  <div class="space-y-8">
    <AppHeader />

    <p class="text-gray-600">Temukan artikel dan tips bermanfaat dari Guru Bimbingan Konseling (BK) sekolah Anda.</p>

    <div class="flex space-x-3 overflow-x-auto pb-2">
      <button v-for="topic in uniqueTopics" :key="topic" @click="selectedTopic = topic" :class="[
        'py-1.5 px-4 rounded-full text-sm font-medium transition duration-200 shrink-0',
        selectedTopic === topic
          ? 'bg-secondary-600 text-white shadow-md'
          : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
      ]">
        {{ topic }}
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <ArticleCard v-for="article in filteredArticles" :key="article.id" :article="article" />
    </div>

    <div v-if="!filteredArticles.length" class="text-center py-10 text-gray-500">
      <p>Tidak ada artikel yang ditemukan untuk topik **{{ selectedTopic }}**.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// 1. Data Dummy Artikel (Ganti dengan fetch data dari API)
const articles = [
  {
    id: 1,
    title: "Mengelola Kecemasan Belajar di Masa Ujian",
    author: "Bpk. Dwi Prasetyo, M.Pd",
    topic: "Kecemasan & Stres",
    date: "12 Des 2025",
    imageUrl: "/static/images/kesmen.png",
    slug: "mengelola-kecemasan-ujian"
  },
  {
    id: 2,
    title: "Tips Memilih Jurusan Kuliah Sesuai Minat dan Bakat",
    author: "Ibu Rina Amalia, S.Psi",
    topic: "Karir & Pendidikan",
    date: "10 Des 2025",
    imageUrl: "/static/images/kesmen.png",
    slug: "tips-memilih-jurusan-kuliah"
  },
  {
    id: 3,
    title: "Pentingnya Batasan dalam Penggunaan Media Sosial",
    author: "Bpk. Budi Santoso, S.Pd",
    topic: "Hubungan Sosial",
    date: "8 Des 2025",
    imageUrl: "/static/images/kesmen.png",
    slug: "batasan-media-sosial"
  },
  {
    id: 4,
    title: "Mengenali Tanda-tanda Burnout pada Siswa",
    author: "Bpk. Dwi Prasetyo, M.Pd",
    topic: "Kecemasan & Stres",
    date: "5 Des 2025",
    imageUrl: "/static/images/kesmen.png",
    slug: "tanda-tanda-burnout-siswa"
  },
];

const selectedTopic = ref('Semua');

// Mendapatkan daftar topik unik untuk filter
const uniqueTopics = computed(() => {
  const topics = new Set(['Semua']);
  articles.forEach(article => topics.add(article.topic));
  return Array.from(topics);
});

// Filter artikel berdasarkan topik yang dipilih
const filteredArticles = computed(() => {
  if (selectedTopic.value === 'Semua') {
    return articles;
  }
  return articles.filter(article => article.topic === selectedTopic.value);
});
</script>