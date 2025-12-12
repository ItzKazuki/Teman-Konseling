<template>
  <div
    class="fixed inset-x-0 bottom-0 left-0 z-40 w-full bg-white h-16 mx-auto overflow-hidden border-t border-gray-200 sm:bottom-5 sm:shadow-lg sm:shadow-base-500/30 hover:shadow-md duration-300 sm:border sm:max-w-md sm:rounded-xl sm:h-16">
    <div class="h-full mx-auto font-medium grid grid-cols-4">
      <NuxtLink v-for="item in props.nav" :key="item.name" :to="item.to"
        class="inline-flex flex-col items-center justify-center px-5 group gap-1 text-base-500"
        :class="isActive(item.to) ? 'text-primary-600' : 'text-gray-400 hover:text-primary-600'">
        <Icon :name="item.icon" class="w-6 h-6" />
        <span class="text-xs">{{ item.name }}</span>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
interface NavItem {
  name: string;
  to: string;
  icon: string;
}

const route = useRoute();
const props = defineProps<{
  nav: NavItem[];
}>();

// cek apakah route aktif
const isActive = (path: string) => {
  return route.path === path || route.path.startsWith(path + '/');
};

</script>