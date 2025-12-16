<template>
  <ClientOnly>
    <template #fallback>
      <div class="space-y-6 animate-pulse">

        <div class="h-12 bg-gray-200 rounded-lg"></div>

        <div class="space-y-2 pt-2">
          <div class="h-8 w-40 bg-gray-300 rounded-md"></div>
        </div>

        <div class="bg-gray-100 border-l-4 border-gray-300 p-4 rounded-lg shadow-sm">
          <div class="h-4 w-32 bg-gray-200 rounded-md mb-3"></div>
          <div class="h-5 w-full bg-gray-200 rounded-md mb-2"></div>
          <div class="h-5 w-3/4 bg-gray-200 rounded-md"></div>
          <div class="h-3 w-16 bg-gray-200 rounded-md mt-3 ml-auto"></div>
        </div>

        <div class="pt-2">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-6 h-6 bg-secondary-200 rounded-full shrink-0"></div>
            <div class="h-6 w-40 bg-gray-300 rounded-md"></div>
          </div>

          <div class="space-y-4">
            <div class="flex gap-4 p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
              <div class="w-20 h-20 bg-gray-200 rounded-lg shrink-0"></div>

              <div class="flex flex-col justify-center w-full">
                <div class="h-3 w-20 bg-secondary-200 rounded-md mb-2"></div>
                <div class="h-5 w-full bg-gray-300 rounded-md mb-1"></div>
                <div class="h-5 w-4/5 bg-gray-300 rounded-md"></div>

                <div class="flex items-center text-xs text-gray-500 mt-2 space-x-3">
                  <div class="h-3 w-16 bg-gray-200 rounded-md"></div>
                  <div class="h-3 w-1 bg-gray-200"></div>
                  <div class="h-3 w-16 bg-gray-200 rounded-md"></div>
                </div>
              </div>
            </div>

            <div class="flex gap-4 p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
              <div class="w-20 h-20 bg-gray-200 rounded-lg shrink-0"></div>

              <div class="flex flex-col justify-center w-full">
                <div class="h-3 w-24 bg-secondary-200 rounded-md mb-2"></div>
                <div class="h-5 w-full bg-gray-300 rounded-md mb-1"></div>
                <div class="h-5 w-3/5 bg-gray-300 rounded-md"></div>

                <div class="flex items-center text-xs text-gray-500 mt-2 space-x-3">
                  <div class="h-3 w-16 bg-gray-200 rounded-md"></div>
                  <div class="h-3 w-1 bg-gray-200"></div>
                  <div class="h-3 w-16 bg-gray-200 rounded-md"></div>
                </div>
              </div>
            </div>

            <div class="text-center pt-2">
              <div class="h-4 w-32 bg-primary-300 rounded-md mx-auto"></div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="space-y-6">

      <AppHeader size="xl" />

      <div class="space-y-2 pt-2">
        <h1 class="text-2xl font-bold text-gray-800">Halo, {{ auth.user?.name }}!</h1>
      </div>

      <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
        <h2 class="text-sm font-semibold text-yellow-700 mb-2">✨ Motivasi Hari Ini</h2>
        <p class="italic text-yellow-800 text-base">
          "Kekuatan terbesar kita adalah keberanian untuk bangkit setelah kita jatuh."
        </p>
        <p class="text-right text-xs text-yellow-600 mt-2">— Teman Konseling</p>
      </div>

      <div class="pt-2">
        <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
          <Icon name="tabler:notebook" class="w-6 h-6 text-secondary-600" />
          Bacaan Buat Kamu
        </h2>

        <div v-if="pending" class="text-center py-10 text-gray-500">
          <p>Memuat artikel...</p>
        </div>
        <div v-else-if="error" class="text-center py-10 text-red-500">
          <p>Gagal memuat artikel. Silakan coba lagi.</p>
        </div>
        <div v-else-if="!articles || articles.length === 0" class="text-center py-10 text-gray-500">
          <p>Saat ini belum ada artikel yang tersedia.</p>
        </div>

        <div v-else class="space-y-4">
          <NuxtLink v-for="article in articles" :key="article.slug" :to="`/articles/${article.slug}`"
            class="flex gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition duration-200 cursor-pointer group">
            <img :src="article.thumbnail_url || '/static/images/default-article.png'"
              :alt="`Thumbnail ${article.title}`"
              class="w-20 h-20 object-cover rounded-lg shrink-0 border border-gray-200 group-hover:border-primary-300 transition duration-200">

            <div class="flex flex-col justify-center">
              <p class="text-xs font-bold text-secondary-600 mb-1 uppercase tracking-wider">
                {{ article.category_name || 'Umum' }}
              </p>

              <h3
                class="text-base font-extrabold text-gray-900 line-clamp-2 group-hover:text-primary-700 transition duration-200">
                {{ article.title }}
              </h3>

              <div class="flex items-center text-xs text-gray-500 mt-1 space-x-2">
                <p class="font-medium">oleh {{ article.author_name || 'Admin BK' }}</p>
                <!-- <span class="text-gray-300">•</span> -->
                <!-- <p>{{ article.read_time || '?' }} menit baca</p> -->
              </div>
            </div>
          </NuxtLink>

          <div class="text-center pt-2">
            <NuxtLink to="/articles"
              class="text-sm font-bold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
              Lihat Semua Artikel
              <Icon name="tabler:arrow-right" class="w-4 h-4" />
            </NuxtLink>
          </div>
        </div>
      </div>

    </div>
  </ClientOnly>
</template>

<script setup lang="ts">
const auth = useAuthStore();
const { data: articles, pending, error } = await useAsyncData(
  'latest-articles',
  async () => {
    // Ganti '/student/articles' dengan endpoint API Anda yang benar
    // Asumsi useApi().get mengembalikan { status: boolean, data: Article[] }
    const res = await useApi().get<{ status: boolean, data: Article[] }>('/student/articles'); 

    if (res.status && Array.isArray(res.data)) {
      // Kita hanya ingin menampilkan 3 artikel terbaru di sini (misalnya)
      return res.data.slice(0, 3);
    }
    return []; // Kembalikan array kosong jika gagal atau tidak ada data
  }
);

const checkDailyMood = async () => {
  try {
    const res = await useApi().get<{ has_filled_today: boolean }>('/student/daily-moods/check');
    
    // Jika BELUM mengisi (has_filled_today === false)
    if (res.status && res.data && !res.data.has_filled_today) {
      // Arahkan ke halaman pilih emosi
      return navigateTo('/mood-picker');
    }
  } catch (error) {
    console.error("Gagal mengecek status emosi harian:", error);
  }
};

onMounted(() => {
  checkDailyMood();
});
</script>