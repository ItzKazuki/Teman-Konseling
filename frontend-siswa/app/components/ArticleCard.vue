<template>
  <NuxtLink :to="`/articles/${article.slug}`" 
    class="block bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
    
    <div class="h-40 overflow-hidden">
      <img :src="article.imageUrl" :alt="article.title" 
           class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
    </div>

    <div class="p-4 space-y-2">
      <span class="inline-block text-xs font-semibold py-1 px-3 rounded-full text-secondary-700 bg-secondary-100">
        {{ article.topic }}
      </span>

      <h3 class="text-lg font-extrabold text-gray-900 line-clamp-2 min-h-12">
        {{ article.title }}
      </h3>

      <div class="flex justify-between items-center pt-2 border-t border-gray-100 text-sm text-gray-500">
        <p class="font-medium text-gray-600">{{ article.author }}</p>
        <p>{{ article.date }}</p>
      </div>
    </div>
  </NuxtLink>
</template>

<script setup>
defineProps({
  article: {
    type: Object,
    required: true,
  },
});
</script>