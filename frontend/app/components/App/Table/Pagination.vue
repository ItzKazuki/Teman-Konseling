<template>
  <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4 border-t border-gray-100 pt-6">
    <div class="text-sm font-medium text-gray-500">
      Menampilkan <span class="text-gray-900 font-bold">{{ from }}</span> -
      <span class="text-gray-900 font-bold">{{ to }}</span> dari
      <span class="text-gray-900 font-bold">{{ meta.total }}</span> total data
    </div>

    <div class="flex items-center space-x-2">
      <button @click="$emit('change', meta.current_page - 1)" :disabled="meta.current_page === 1"
        class="p-2 border border-gray-200 rounded-xl flex items-center hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
        <Icon name="tabler:chevron-left" class="w-5 h-5" />
      </button>

      <div class="flex items-center px-4 py-2">
        <span class="text-xs font-black text-gray-900">
          HALAMAN {{ meta.current_page }} / {{ meta.last_page }}
        </span>
      </div>

      <button @click="$emit('change', meta.current_page + 1)" :disabled="meta.current_page === meta.last_page"
        class="p-2 border border-gray-200 rounded-xl flex items-center hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
        <Icon name="tabler:chevron-right" class="w-5 h-5" />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    has_more_pages: boolean;
  }
}>();

defineEmits(['change']);

const from = computed(() => (props.meta.current_page - 1) * props.meta.per_page + 1);
const to = computed(() => Math.min(props.meta.current_page * props.meta.per_page, props.meta.total));
</script>