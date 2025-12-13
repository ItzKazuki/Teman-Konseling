<template>
  <div class="space-y-4">
    <div class="flex justify-between items-center pb-2 border-b border-gray-100 mb-8">
      <AppBackButton />
      <img src="/static/teman-konseling-sm.svg" alt="Logo Teman Konseling" class="h-6 w-auto" />
    </div>

    <div v-if="tentang" class="bg-gray-50 px-6 py-5 rounded-2xl shadow-sm">
      <ContentRenderer :value="tentang" class="prose prose-base font-montserrat max-w-none" />
      <p class="mt-3 text-xs text-gray-500">
        Last update: {{ tentang.meta?.last_update }}
      </p>
    </div>

    <div v-else class="text-sm text-gray-600 italic">
      Data faq-address sistem ini belum tersedia.
    </div>
  </div>

</template>

<script setup lang="ts">
const { data: tentang } = await useAsyncData(() => queryCollection('content').path('/faq-address').first())
</script>