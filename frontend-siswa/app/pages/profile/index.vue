<template>
  <div class="space-y-10">
    <AppHeader />

    <div class="flex items-center flex-col gap-3">
      <img src="/static/images/profile.png" alt="Profile" class="w-32 object-cover rounded-full" />

      <div class="text-center">
        <h3 class="text-lg font-bold">Chaeza Ibnu Akbar</h3>
        <p class="text-sm text-gray-500">Student</p>
      </div>
    </div>

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

function handleLogout() {
  useAlert().success('Berhasil Logout')

  navigateTo('/auth/login')
}

</script>