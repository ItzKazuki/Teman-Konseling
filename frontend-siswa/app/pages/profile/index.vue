<template>
  <div class="space-y-8">
    <AppHeader />

    <ClientOnly>
      <template #fallback>
        <div class="pt-4 pb-6 border-b border-gray-200 flex items-center flex-col gap-3">
          <div class="w-28 h-28 bg-gray-300 rounded-full"></div>

          <div class="text-center space-y-1.5">
            <div class="h-6 w-32 bg-gray-300 rounded-md mx-auto"></div>
            <div class="h-4 w-16 bg-primary-200 rounded-md mx-auto"></div>
          </div>
        </div>
      </template>

      <div class="pt-4 pb-6 border-b border-gray-100 flex items-center flex-col gap-3">

        <div class="relative w-28 h-28">
          <img :src="auth.user?.avatar_url" alt="Profile"
            class="w-full h-full object-cover rounded-full border-4 border-white shadow-lg" />
        </div>

        <div class="text-center space-y-0.5">
          <h3 class="text-xl font-extrabold text-gray-900">{{ auth.user?.name }}</h3>
          <p class="text-sm text-primary-600 font-semibold">Student</p>
        </div>

      </div>
    </ClientOnly>

    <div class="space-y-4">
      <div class="space-y-2">
        <h4 class="text-md text-gray-600 font-semibold">Akun Saya</h4>

        <div class="bg-white border-gray-300 border flex flex-col rounded-xl shadow-xl divide-y divide-gray-300">
          <AppMenuButton :items="myAccountMenu" />
        </div>
      </div>

      <div class="space-y-2">
        <h4 class="text-md text-gray-600 font-semibold">Presensi</h4>

        <div class="bg-white border border-gray-300 flex flex-col rounded-xl shadow-xl divide-y divide-gray-300">
          <AppMenuButton :items="detailAppMenu" />
        </div>
      </div>
      <p class="text-center text-gray-500 text-xs">{{ config.public.buildVersion }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
interface MenuButtonItem {
  label: string
  icon: string
  to?: string
  onClick?: () => void
  variant?: 'default' | 'danger'
}

const auth = useAuthStore();
const config = useRuntimeConfig()

const myAccountMenu: MenuButtonItem[] = [
  {
    label: 'Edit Akun',
    icon: 'mdi:account-edit',
    to: '/profile/edit'
  },
];

const detailAppMenu: MenuButtonItem[] = [
  {
    icon: 'mdi:information-outline',
    label: 'Tentang',
    to: '/tentang'
  },
  {
    icon: 'mdi:logout-variant',
    label: 'Keluar',
    variant: 'danger',
    onClick: handleLogout
  }
];

async function handleLogout() {
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
  }
}
</script>