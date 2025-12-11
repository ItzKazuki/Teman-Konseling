<template>
  <div class="flex justify-between text-black">
    <img :src="logo" alt="Logo Ilustrator" />

    <NuxtLink to="/notifications">
      <Icon name="tk:notification-bold" size="25" />
    </NuxtLink>
  </div>
</template>

<script setup>
const props = defineProps({
  size: {
    type: String,
    default: "sm"
  }
})

const logo = computed(() => `/static/teman-konseling-${props.size}.svg`)
</script>