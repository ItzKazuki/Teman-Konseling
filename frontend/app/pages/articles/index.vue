<template>
  <div v-if="can(['bk', 'staff'])" class="col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-primary-100 rounded-full flex items-center text-primary-600">
          <Icon name="tabler:news" class="w-6 h-6 transition-transform group-hover:scale-110" />
        </div>
        <div>
          <div class="text-2xl font-bold">{{ totalArticles }}</div>
          <div class="text-xs text-gray-500">Total Artikel</div>
        </div>
      </div>
    </div>

    <div class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-green-100 rounded-full flex items-center text-green-600">
          <Icon name="tabler:eye" class="w-6 h-6 transition-transform group-hover:scale-110" />
        </div>
        <div>
          <div class="text-2xl font-bold">{{ totalViews }}</div>
          <div class="text-xs text-gray-500">Total Pembaca</div>
        </div>
      </div>
    </div>

    <div class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-orange-100 rounded-full flex items-center text-orange-600">
          <Icon name="tabler:chart-line" class="w-6 h-6 transition-transform group-hover:scale-110" />
        </div>
        <div>
          <div class="text-2xl font-bold">{{ avgViews }}</div>
          <div class="text-xs text-gray-500">Rata-rata Pembaca</div>
        </div>
      </div>
    </div>

    <div class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-red-100 rounded-full flex items-center text-red-600">
          <Icon name="tabler:file-pencil" class="w-6 h-6 transition-transform group-hover:scale-110" />
        </div>
        <div>
          <div class="text-2xl font-bold">{{ draftArticles }}</div>
          <div class="text-xs text-gray-500">Artikel Draft</div>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">

      <h3 class="text-2xl font-semibold text-gray-800">Daftar Artikel</h3>

      <div class="flex items-center space-x-3">

        <button @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <NuxtLink to="/articles/create"
          class="flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md">
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Artikel
        </NuxtLink>
      </div>
    </div>

    <div v-if="isFilterVisible"
      class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
      <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Artikel</h4>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" v-model="filters.search" placeholder="Cari Nama atau slug Artikel"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        <FormSelect name="filter-status" placeholder="Semua Status" v-model="filters.status" :options="[
          { label: 'Draft', value: 'draft' },
          { label: 'Published', value: 'published' },
          { label: 'Archived', value: 'archived' },
        ]" allow-placeholder-selection />
        <FormSelect name="category-article" placeholder="Semua Kategori" v-model="filters.article_category_id"
          :options="categories.map(cat => ({ label: cat.name, value: cat.id }))" allow-placeholder-selection />
        <button @click="applyFilter"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 md:col-span-3 lg:col-span-1">
          Terapkan Filter
        </button>
      </div>
    </div>


    <div class="overflow-x-auto border border-gray-200 rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Judul Artikel
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Author
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Kategori
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Views
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Aksi
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">

          <tr v-if="data.length === 0">
            <td colspan="6" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <div class="flex items-center justify-center">
                <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
                <p>Tidak ada Artikel yang ditemukan.</p>
              </div>
            </td>
          </tr>

          <tr v-for="article in data" :key="article.id" class="hover:bg-primary-50/50 transition-colors duration-100">

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
              {{ article.title }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ article.author_name || '-' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ article.category_name || '-' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span :class="getStatusArticleClass(article.status)"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg transition-all duration-300 border capitalize">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>

                {{ article.status }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
              {{ article.views }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <NuxtLink :to="`/articles/${article.id}`"
                class="text-primary-600 hover:text-primary-800 transition-colors p-1 rounded hover:bg-primary-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </NuxtLink>
              <button @click="handleDelete(article.id, article.title)" v-if="can(['bk', 'staff'])"
                class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-100/50">
                <Icon name="tabler:trash" class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <AppTablePagination :meta="meta" @change="changePage" v-if="data.length > 0" />

  </div>
</template>

<script setup lang="ts">
const { can } = usePermission();
const {
  data,
  loading,
  meta,
  filters,
  fetchData,
  changePage,
  applyFilter
} = useDataTable<Article, { search: string; status: string, article_category_id: string }>('/articles', {
  search: '',
  status: '',
  article_category_id: ''
});

const categories = ref<ArticleCategory[]>([]);

const isFilterVisible = ref<boolean>(false);

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const draftArticles = computed(() =>
  data.value.filter(a => a.status === 'draft').length
);

const avgViews = computed(() => {
  if (!data.value.length) return 0;
  return Math.round(totalViews.value / data.value.length);
});

const totalViews = computed(() =>
  data.value.reduce((sum, a) => sum + (a.views || 0), 0)
);

const totalArticles = computed(() => data.value.length);

const handleDelete = async (id?: string, name?: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus artikel "${name}"?`);

    if (!confirmed) return;

    const message = await useApi().destroy(`/articles/${id}`);

    if (message.status) {
      useToast().success(`Kategori Artikel "${name}" berhasil dihapus.`);
      await fetchData();
    } else {
      useToast().error('Gagal menghapus kategori artikel. Silakan coba lagi.');
    }
  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  }
};

async function fetchCategoryArticle() {
  try {
    const res = await useApi().get<ArticleCategory[]>('/reference/article-categories');
    if (res.status && res.data) {
      categories.value = res.data;
    } else {
      useToast().error('Gagal memuat daftar kategori.');
    }
  } catch (e) {
    useToast().error('Error saat mengambil data kategori.');
  }
}

onMounted(() => {
  fetchData();
  fetchCategoryArticle();
})
</script>