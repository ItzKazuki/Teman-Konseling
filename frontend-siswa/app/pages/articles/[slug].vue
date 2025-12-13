<template>
  <div>
    <div class="flex justify-between items-center pb-2 border-b border-gray-100 mb-8">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div v-if="pending" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <div class="animate-pulse space-y-8">
        <div class="w-full h-80 bg-gray-200 rounded-xl"></div>

        <div class="space-y-4 pb-4 border-b border-gray-200">
          <div class="h-4 w-24 bg-gray-200 rounded-full"></div>
          <div class="h-8 w-3/4 bg-gray-200 rounded-lg"></div>
          <div class="flex space-x-4">
            <div class="h-4 w-20 bg-gray-200 rounded-full"></div>
            <div class="h-4 w-32 bg-gray-200 rounded-full"></div>
          </div>
        </div>

        <div class="space-y-3">
          <div class="h-4 bg-gray-200 rounded"></div>
          <div class="h-4 w-11/12 bg-gray-200 rounded"></div>
          <div class="h-4 w-10/12 bg-gray-200 rounded"></div>
          <div class="h-4 w-9/12 bg-gray-200 rounded"></div>
          <div class="h-4 w-full bg-gray-200 rounded"></div>
        </div>
      </div>
    </div>

    <article v-else-if="article" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <div class="space-y-4">
        <img :src="article.thumbnail_url" :alt="article.title"
          class="w-full h-auto object-cover rounded-xl shadow-md max-h-96" />

        <span class="inline-block text-sm font-semibold py-1 px-4 rounded-full text-secondary-700 bg-secondary-100">
          {{ article.category_name }}
        </span>
      </div>

      <header class="space-y-2 pb-4 border-b border-gray-200">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
          {{ article.title }}
        </h1>

        <div class="flex items-center space-x-4 text-sm text-gray-600">
          <div class="flex items-center space-x-2">
            <Icon name="tabler:user-circle" class="w-5 h-5 text-gray-500" />
            <p class="font-semibold text-gray-800">{{ article.author_name }}</p>
          </div>
          <span class="text-gray-300">|</span>
          <div class="flex items-center space-x-1.5">
            <Icon name="tabler:calendar" class="w-5 h-5 text-gray-500" />
            <time :datetime="article.created_at" class="font-medium text-gray-700">{{
              formatHummanDate(article.created_at)
              }}</time>
          </div>
        </div>
      </header>

      <section class="prose max-w-none prose-lg text-gray-800 space-y-6">
        <div v-html="article.content"></div>
      </section>

      <footer class="pt-8 border-t border-gray-200 text-center">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Butuh Bantuan Lebih Lanjut?</h3>
        <NuxtLink to="/chats"
          class="inline-flex items-center justify-center py-3 px-6 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition duration-200 shadow-md">
          Mulai Konseling dengan Guru BK
        </NuxtLink>
      </footer>
    </article>

    <div v-else class="text-center py-20">
      <div class="mx-auto">
        <img src="/static/404.svg" class="p-6" alt="">
        <p class="text-6xl font-bold text-primary-800">404</p>
      </div>
      <h1 class="text-3xl font-bold text-gray-900 mt-4">Artikel Tidak Ditemukan</h1>
      <p class="text-gray-600 mt-2">Maaf, artikel yang Anda cari mungkin telah dihapus atau URL salah.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const slug = computed(() => route.params.slug as string);

const { data: article, pending, error } = await useAsyncData(
  `article-${slug.value}`,
  async () => {
    if (!slug.value) return null;

    try {
      const res = await useApi().get<Article>(`/student/articles/${slug.value}`);

      if (res.status && res.data) {
        return res.data;
      } else {
        return null;
      }
    } catch (e) {
      return null;
    }
  },
  {
    server: true,
  }
);

useHead({
  title: article.value?.title || 'Memuat Artikel...'
});

</script>

<style scoped>
.prose {
  font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
}
</style>