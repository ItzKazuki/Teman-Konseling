<template>
  <div class="min-h-screen">
    <main class="mx-6 my-8 pb-16">
      <slot />
    </main>

    <ClientOnly>
      <AppNavbar :nav="navItems" />
    </ClientOnly>

  </div>
</template>

<script setup lang="ts">
const navItems = [
  { name: 'Home', to: '/home', icon: 'tk:home-bold' },
  { name: 'Konseling', to: '/chats', icon: 'tk:chat-bold' },
  { name: 'Emotion', to: '/emotions', icon: 'tk:emotion-bold' },
  { name: 'Profile', to: '/profile', icon: 'tk:profile-bold' },
];
</script>