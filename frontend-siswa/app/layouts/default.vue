<template>
  <template v-if="showNavbar">
    <div class="min-h-screen">
      <main class="mx-6 my-8 pb-16">
        <slot />
      </main>
      <AppNavbar :show="showNavbar" :nav="navItems" />
    </div>
  </template>

  <template v-else>
    <main class="mx-6 my-8">
      <slot />
    </main>
  </template>

</template>

<script setup lang="ts">
const route = useRoute()

// Daftar navbar dan logika hideNavbarRoutes tidak berubah
const navItems = [
  { name: 'Home', to: '/home', icon: 'tk:home-bold' },
  { name: 'Konseling', to: '/chat', icon: 'tk:chat-bold' },
  { name: 'Emotion', to: '/emotion', icon: 'tk:emotion-bold' },
  { name: 'Profile', to: '/profile', icon: 'tk:profile-bold' },
];

const hideNavbarRoutes = ['/', '/auth', '/mood-picker'];

const showNavbar = computed(() => {
  return !hideNavbarRoutes.some(path =>
    route.path === path || route.path.startsWith(path + '/')
  )
})
</script>