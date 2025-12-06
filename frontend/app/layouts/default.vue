<template>
  <div class="flex h-screen bg-gray-50 text-gray-800">

    <aside class="w-64 shrink-0 bg-white shadow-xl flex flex-col">
      <div class="p-6">
        <img src="/static/teman-konseling-md.svg" class="mx-auto" alt="TemanKonseling">
      </div>
      <nav class="grow p-4 space-y-2 overflow-y-auto">

        <NuxtLink v-for="item in navItems" :key="item.name" :to="item.to" :class="getLinkClass(item.to)">
          <Icon :name="item.icon" class="w-5 h-5 mr-3" />
          {{ item.name }}
        </NuxtLink>

        <div class="pt-4 mt-4 border-t border-gray-100 space-y-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">Master Data</p>

          <NuxtLink v-for="item in masterData" :key="item.name" :to="item.to" :class="getLinkClass(item.to)">
            <Icon :name="item.icon" class="w-5 h-5 mr-3" />
            {{ item.name }}
          </NuxtLink>
        </div>
      </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
      <header class="mb-8 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">Dashboard</h2>
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <img src="http://api.sistem-kinerja.test/static/profile.png" alt="User Avatar"
              class="w-10 h-10 rounded-full">
            <div class="text-sm">
              <div class="font-medium">Robert Pattinson</div>
              <div class="text-xs text-gray-500">Super Admin</div>
            </div>
          </div>
        </div>
      </header>

      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()

const navItems = [
  {
    name: 'Dashboard',
    to: '/dashboard',
    icon: 'tabler:dashboard'
  },
  {
    name: 'Moods',
    to: '/moods',
    icon: 'tabler:layout-dashboard',
  },

  // Kategori dengan sub-menu/chevron kanan
  {
    name: 'User',
    to: '/users',
    icon: 'tabler:user-circle',
  },
  {
    name: 'Siswa',
    to: '/students',
    icon: 'tabler:adjustments',
  },
]

const masterData = [
  {
    name: 'Kelas',
    to: '/master-data/classrooms',
    icon: 'tabler:bell'
  },
]

const getLinkClass = (itemPath: string) => {
  const isActive = route.path === itemPath ||
    (itemPath !== '/' && route.path.startsWith(itemPath))

  const baseClass = 'flex items-center p-3 text-sm font-medium rounded-lg transition-colors'

  if (isActive) {
    return `${baseClass} bg-blue-50 text-blue-600 shadow-inner`
  } else {
    return `${baseClass} text-gray-500 hover:bg-gray-100`
  }
}
</script>