<template>
  <div class="flex h-screen bg-gray-50 text-gray-800">

    <aside class="w-64 shrink-0 bg-white shadow-xl flex flex-col">
      <div class="p-6">
        <img src="/static/teman-konseling-md.svg" class="mx-auto" alt="TemanKonseling">
      </div>
      <nav class="grow p-4 space-y-2 border-t border-gray-100 overflow-y-auto">
        <NuxtLink v-for="item in filteredNavItems" :key="item.name" :to="item.to" :class="getLinkClass(item.to)">
          <Icon :name="item.icon" class="w-5 h-5 mr-3" />
          {{ item.name }}
        </NuxtLink>

        <div v-if="filteredMasterData.length > 0" class="pt-4 mt-4 border-t border-gray-100 space-y-2">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">Master Data</p>

          <NuxtLink v-for="item in filteredMasterData" :key="item.name" :to="item.to" :class="getLinkClass(item.to)">
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

            <div class="relative inline-block">
              <img :src="auth.user?.avatar_url" alt="User Avatar"
                class="w-10 h-10 rounded-full object-cover border border-gray-100">

              <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full border-2 border-white"
                :class="true ? 'bg-emerald-500' : 'bg-gray-300'" :title="true ? 'Online' : 'Offline'"></span>
            </div>

            <div class="text-sm hidden sm:block text-left">
              <div class="font-medium text-gray-800">{{ auth.user?.name }}</div>
              <div class="text-xs text-gray-500">{{ auth.user?.jabatan }}</div>
            </div>

            <Icon name="tabler:chevron-down" :class="{ 'rotate-180': isProfileDropdownOpen }"
              class="w-4 h-4 text-gray-600 transition-transform hidden sm:block" />
          </button>

          <div v-if="isProfileDropdownOpen" id="profile-menu"
            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl z-10 border border-gray-100 origin-top-right animate-fade-in overflow-hidden">
            <div class="p-2 space-y-1">

              <div class="px-4 py-3 bg-gray-50 rounded-xl mb-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status Konselor</span>
                  <button @click="toggleAvailability"
                    :class="auth.user?.is_available ? 'bg-emerald-500' : 'bg-gray-300'"
                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none">
                    <span :class="auth.user?.is_available ? 'translate-x-4' : 'translate-x-0'"
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                  </button>
                </div>
                <p class="text-[10px] mt-1" :class="auth.user?.is_available ? 'text-emerald-600' : 'text-gray-400'">
                  {{ auth.user?.is_available ? '● Siap menerima konseling' : '○ Sedang tidak menerima' }}
                </p>
              </div>

              <NuxtLink to="/profile" @click="isProfileDropdownOpen = false"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <Icon name="tabler:user-circle" class="w-5 h-5 mr-3 text-primary-500" />
                Edit Profile
              </NuxtLink>

              <div class="border-t border-gray-100 my-1"></div>

              <button @click="handleLogout"
                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
  { name: 'Dashboard', to: '/dashboard', icon: 'tabler:layout-dashboard', roles: ['bk', 'guru', 'staff'] },
  { name: 'Artikel', to: '/articles', icon: 'tabler:news', roles: ['bk', 'guru', 'staff'] },
  { name: 'Mood Siswa', to: '/moods', icon: 'tabler:heart-rate-monitor', roles: ['bk', 'guru'] },
  { name: 'Konseling', to: '/counseling', icon: 'tabler:messages', roles: ['bk'] },
  { name: 'User', to: '/users', icon: 'tabler:user-circle', roles: ['bk'] },
  { name: 'Siswa', to: '/students', icon: 'tabler:school', roles: ['bk', 'guru', 'staff'] },
];

const masterData = [
  { name: 'Kelas', to: '/master-data/classrooms', icon: 'tabler:door', roles: ['bk', 'staff'] },
  { name: 'Kategori Artikel', to: '/master-data/article-category', icon: 'tabler:list-details', roles: ['bk', 'staff'] },
];

const filteredNavItems = computed(() => {
  return navItems.filter(item => item.roles.includes(auth.user?.role as string));
});

const filteredMasterData = computed(() => {
  return masterData.filter(item => item.roles.includes(auth.user?.role as string));
});

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

const isProfileDropdownOpen = ref(false);

const toggleDropdown = () => {
  isProfileDropdownOpen.value = !isProfileDropdownOpen.value;
};

const toggleAvailability = async () => {
  try {
    if (auth.user) {
      auth.user.is_available = !auth.user.is_available;
    }

    await useApi().patch('/admin/profile/status', {
      is_available: auth.user?.is_available
    });

    useToast().success(`Status diubah ke: ${auth.user?.is_available ? 'Tersedia' : 'Sibuk'}`);
  } catch (err) {
    if (auth.user) {
      auth.user.is_available = !auth.user.is_available;
    }
    useToast().error('Gagal memperbarui status ketersediaan');
  }
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