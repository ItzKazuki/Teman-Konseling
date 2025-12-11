<template>
  <div class="bg-white border border-gray-100 flex flex-col rounded-xl shadow-sm divide-y divide-gray-300">
    <component v-for="(item, idx) in items" :key="idx" :is="item.to ? NuxtLink : 'button'" :to="item.to"
      @click="item.onClick && item.onClick()" :class="[
        'px-5 py-4 flex items-center gap-3 text-sm transition w-full text-left',
        'hover:bg-gray-50',
        item.variant === 'danger' && 'text-red-600 hover:bg-red-50',
        'first:rounded-t-xl last:rounded-b-xl'
      ]">
      <Icon :name="item.icon" size="25" />
      <div class="w-full flex justify-between items-center">
        <p>{{ item.label }}</p>
        <Icon name="mdi:chevron-right" size="20" />
      </div>
    </component>
  </div>

</template>


<script setup lang="ts">
import { NuxtLink } from '#components'

interface MenuButtonItem {
  label: string
  icon: string
  to?: string
  onClick?: () => void
  variant?: 'default' | 'danger'
}

defineProps<{
  items: MenuButtonItem[]
}>()
</script>
