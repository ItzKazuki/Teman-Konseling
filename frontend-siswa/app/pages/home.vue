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
        <h1 class="text-2xl font-bold text-gray-800">Halo, Chaeza!</h1>
      </div>

      <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
        <h2 class="text-sm font-semibold text-yellow-700 mb-2">✨ Motivasi Hari Ini</h2>
        <p class="italic text-yellow-800 text-base">
          "Kekuatan terbesar kita adalah keberanian untuk bangkit setelah kita jatuh."
        </p>
        <p class="text-right text-xs text-yellow-600 mt-2">— Anonymous</p>
      </div>

      <div class="pt-2">
        <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
          <Icon name="tabler:notebook" class="w-6 h-6 text-secondary-600" />
          Bacaan Buat Kamu
        </h2>

        <div class="space-y-4">
          <NuxtLink to="/articles/mengelola-kecemasan-ujian"
            class="flex gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition duration-200 cursor-pointer group">

            <img src="/static/images/kesmen.png" alt="Thumbnail Artikel"
              class="w-20 h-20 object-cover rounded-lg shrink-0 border border-gray-200 group-hover:border-primary-300 transition duration-200">

            <div class="flex flex-col justify-center">
              <p class="text-xs font-bold text-secondary-600 mb-1 uppercase tracking-wider">Kesehatan Mental</p>

              <h3
                class="text-base font-extrabold text-gray-900 line-clamp-2 group-hover:text-primary-700 transition duration-200">
                5 Cara Efektif Mengelola Rasa Cemas Saat Ujian
              </h3>

              <div class="flex items-center text-xs text-gray-500 mt-1 space-x-2">
                <p class="font-medium">oleh Dr. Rina</p>
                <span class="text-gray-300">•</span>
                <p>5 menit baca</p>
              </div>
            </div>
          </NuxtLink>

          <NuxtLink to="/articles/slug-artikel-2"
            class="flex gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition duration-200 cursor-pointer group">

            <img src="/static/images/kesmen.png" alt="Thumbnail Artikel"
              class="w-20 h-20 object-cover rounded-lg shrink-0 border border-gray-200 group-hover:border-primary-300 transition duration-200">

            <div class="flex flex-col justify-center">
              <p class="text-xs font-bold text-secondary-600 mb-1 uppercase tracking-wider">Kesejahteraan Diri</p>

              <h3
                class="text-base font-extrabold text-gray-900 line-clamp-2 group-hover:text-primary-700 transition duration-200">
                Pentingnya Batasan Digital untuk Pelajar
              </h3>

              <div class="flex items-center text-xs text-gray-500 mt-1 space-x-2">
                <p class="font-medium">oleh Dr. Rina</p>
                <span class="text-gray-300">•</span>
                <p>10 menit baca</p>
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