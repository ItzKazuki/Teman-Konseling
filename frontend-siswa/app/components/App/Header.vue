<template>
  <div class="flex justify-between text-black">
    <img :src="logo" alt="Logo Ilustrator" />

    <Icon name="tk:notification-bold" size="25" />
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