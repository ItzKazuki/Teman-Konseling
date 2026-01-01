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

    <div v-if="isLoading" class="flex flex-col items-center justify-center py-20">
      <div class="relative flex items-center justify-center">
        <div class="absolute inset-0 bg-secondary-100 rounded-full blur-xl opacity-50 animate-pulse"></div>
        <Icon name="svg-spinners:ring-resize" class="w-12 h-12 text-secondary-600 relative z-10" />
      </div>
      <p class="mt-4 text-gray-500 font-medium animate-pulse">Mencari artikel menarik...</p>
    </div>

    <div v-else-if="filteredArticles.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <ArticleCard v-for="article in filteredArticles" :key="article.id" :article="article" />
    </div>

    <div v-else
      class="flex flex-col items-center justify-center py-16 px-6 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
      <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-6">
        <Icon name="tabler:news-off" class="w-10 h-10 text-gray-300" />
      </div>
      <h3 class="text-xl font-bold text-gray-800">Belum ada artikel</h3>
      <p class="text-gray-500 max-w-xs mx-auto mt-2">
        Sepertinya belum ada artikel untuk kategori <span class="text-secondary-600 font-semibold">"{{ selectedCategory
          }}"</span> saat ini.
      </p>
      <button v-if="selectedCategory !== 'Semua'" @click="selectedCategory = 'Semua'"
        class="mt-6 text-sm font-bold text-secondary-600 hover:text-secondary-700 flex items-center gap-2">
        <Icon name="tabler:arrow-left" class="w-4 h-4" />
        Kembali ke Semua Artikel
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const toast = useToast();
const articles = ref<Article[]>([]);
const categories = ref<ArticleCategory[]>([]);
const selectedCategory = ref('Semua');
const isLoading = ref(true);

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

const uniqueCategories = computed(() => {
  const categoryNames = ['Semua'];

  categories.value.forEach(cat => categoryNames.push(cat.name));

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

onMounted(async () => {
  isLoading.value = true;
  await Promise.all([
    fetchCategories(),
    fetchArticles()
  ]);
  isLoading.value = false;
});
</script>