<template>
  <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-8 px-2">
    <div class="text-sm font-medium text-gray-500">
      Menampilkan <span class="text-gray-900 font-bold">{{ from }}</span> sampai 
      <span class="text-gray-900 font-bold">{{ to }}</span> dari 
      <span class="text-gray-900 font-bold">{{ meta.total }}</span> data
    </div>

    <div class="flex items-center gap-1 bg-white p-1 rounded-2xl border border-gray-100 shadow-sm">
      <button 
        @click="$emit('change', meta.current_page - 1)"
        :disabled="meta.current_page === 1"
        class="p-2.5 rounded-xl hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all text-gray-600"
      >
        <Icon name="tabler:chevron-left" class="w-5 h-5" />
      </button>

      <div class="flex items-center gap-1">
        <div class="px-4 py-2 bg-primary-50 rounded-xl">
          <span class="text-xs font-black text-primary-700 uppercase tracking-tighter">
            Halaman {{ meta.current_page }} / {{ meta.last_page }}
          </span>
        </div>
      </div>

      <button 
        @click="$emit('change', meta.current_page + 1)"
        :disabled="meta.current_page === meta.last_page"
        class="p-2.5 rounded-xl hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed transition-all text-gray-600"
      >
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
  }
}>();

defineEmits(['change']);

const from = computed(() => (props.meta.current_page - 1) * props.meta.per_page + 1);
const to = computed(() => Math.min(props.meta.current_page * props.meta.per_page, props.meta.total));
</script>