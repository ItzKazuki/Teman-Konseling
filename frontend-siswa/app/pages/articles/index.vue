<template>
  <div class="space-y-8">
    <AppHeader />

    <p class="text-gray-600">Temukan artikel dan tips bermanfaat dari Guru Bimbingan Konseling (BK) sekolah Anda.</p>

    <div class="flex space-x-3 overflow-x-auto pb-2 no-scrollbar">
      <button v-for="category in uniqueCategories" :key="category" @click="selectedCategory = category" :class="[
        'py-1.5 px-4 rounded-full text-sm font-medium transition duration-200 shrink-0',
        selectedCategory === category
          ? 'bg-secondary-600 text-white shadow-md'
          : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
      ]">
        {{ category }}
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <ArticleCard v-for="article in filteredArticles" :key="article.id" :article="article" />
    </div>

    <div v-if="!filteredArticles.length" class="text-center py-10 text-gray-500">
      <p>Tidak ada artikel yang ditemukan untuk kategori {{ selectedCategory }}.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const toast = useToast() // Asumsi Anda menggunakan useToast

const articles = ref<Article[]>([]);
const categories = ref<ArticleCategory[]>([]); // State baru untuk menyimpan data kategori dari API
const selectedCategory = ref('Semua'); // Mengganti selectedTopic

// Contoh pengambilan data kategori
const fetchCategories = async () => {
  try {
    // Ganti 'useApi(true)' dengan cara Anda memanggil API
    const res = await useApi(true).get('/reference/article-categories');

    if (res.status && Array.isArray(res.data)) {
      categories.value = res.data;
    } else {
      toast.error('Gagal memuat kategori artikel.');
    }
  } catch (err) {
    console.error('Error fetching categories:', err);
    toast.error('Gagal memuat kategori artikel.');
  }
};

// Contoh pengambilan data artikel (asumsi Anda sudah memiliki ini)
const fetchArticles = async () => {
  try {
    const res = await useApi(true).get('/student/articles');

    if (res.status && Array.isArray(res.data)) {
      articles.value = res.data;
    }
  } catch (err) {
    console.error('Error fetching articles:', err);
    toast.error('Gagal memuat artikel.');
  }
};


onMounted(() => {
  fetchCategories();
  // Panggil fetch articles juga
  fetchArticles();
});

const uniqueCategories = computed(() => {
  // Tambahkan 'Semua' di awal
  const categoryNames = ['Semua'];

  // Ambil nama-nama kategori dari state `categories` yang telah diambil dari API
  categories.value.forEach(cat => categoryNames.push(cat.name));

  // Jika Anda ingin memastikan kategori yang ada di artikel juga muncul, 
  // meskipun tidak ada di master-data (opsional)
  /*
  articles.value.forEach(article => {
    if (!categoryNames.includes(article.category_name)) {
      categoryNames.push(article.category_name);
    }
  });
  */

  return Array.from(new Set(categoryNames)); // Gunakan Set untuk memastikan keunikan
});

// Filter artikel berdasarkan kategori yang dipilih
const filteredArticles = computed(() => {
  if (selectedCategory.value === 'Semua') {
    return articles.value;
  }
  // Filter berdasarkan nama kategori
  return articles.value.filter(article => article.category_name === selectedCategory.value);
});
</script>