<template>
  <div class="flex h-screen bg-gray-50 text-gray-800">

    <aside class="w-64 shrink-0 bg-white shadow-xl flex flex-col">
      <div class="p-6">
        <img src="/static/teman-konseling-md.svg" class="mx-auto" alt="TemanKonseling">
      </div>
      <nav class="grow p-4 space-y-2 border-t border-gray-100 overflow-y-auto">

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

    <main class="flex-1 overflow-y-auto p-6">
      <header class="mb-8 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">Dashboard</h2>

        <div class="relative">

          <button @click="toggleDropdown"
            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors"
            :aria-expanded="isProfileDropdownOpen" aria-controls="profile-menu">
            <img :src="auth.user?.avatar_url" alt="User Avatar"
              class="w-10 h-10 rounded-full">
            <div class="text-sm hidden sm:block text-left">
              <div class="font-medium text-gray-800">{{ auth.user?.name }}</div>
              <div class="text-xs text-gray-500">{{ auth.user?.jabatan }}</div>
            </div>
            <Icon name="tabler:chevron-down" :class="{ 'rotate-180': isProfileDropdownOpen }"
              class="w-4 h-4 text-gray-600 transition-transform hidden sm:block" />
          </button>

          <div v-if="isProfileDropdownOpen" id="profile-menu"
            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 border border-gray-100 origin-top-right animate-fade-in">
            <div class="py-1">

              <NuxtLink to="/profile" @click="isProfileDropdownOpen = false"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <Icon name="tabler:user-circle" class="w-5 h-5 mr-3 text-primary-500" />
                Edit Profile
              </NuxtLink>

              <div class="border-t border-gray-100 my-1"></div>

              <button @click="handleLogout"
                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                <Icon name="tabler:logout" class="w-5 h-5 mr-3" />
                Logout
              </button>
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

const auth = useAuthStore();

const navItems = [
  {
    name: 'Dashboard',
    to: '/dashboard',
    icon: 'tabler:dashboard'
  },
  {
    name: 'Artikel',
    to: '/articles',
    icon: 'tabler:layout-dashboard',
  },
  {
    name: 'Mood Siswa',
    to: '/moods',
    icon: 'tabler:layout-dashboard',
  },
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
  {
    name: 'Kategori Artikel',
    to: '/master-data/article-category',
    icon: 'tabler:bell'
  },
]

const getLinkClass = (itemPath: string) => {
  const isActive = route.path === itemPath ||
    (itemPath !== '/' && route.path.startsWith(itemPath))

  const baseClass = 'flex items-center p-3 text-sm font-medium rounded-lg transition-colors'

  if (isActive) {
    return `${baseClass} bg-primary-50 text-primary-600 shadow-inner`
  } else {
    return `${baseClass} text-gray-500 hover:bg-gray-100`
  }
}

// State untuk mengontrol visibilitas dropdown
const isProfileDropdownOpen = ref(false);

const toggleDropdown = () => {
  isProfileDropdownOpen.value = !isProfileDropdownOpen.value;
};

const handleLogout = async () => {
  try {
    const confirmed = await useAlert().confirm('Apakah kamu yakin mau kelar?');

    if (!confirmed) return;

    await auth.logout();

    useToast().success('Logout Berhasil, Selamat tinggal!')

    navigateTo('/auth/login')
  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  } finally {
    isProfileDropdownOpen.value = false;
  }
};
</script>