<template>
  <div class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md">

      <div class="flex justify-center mb-8">
        <img src="/static/teman-konseling-md.svg" alt="Logo Ilustrator" class="h-10 w-auto" />
      </div>

      <div class="flex flex-col space-y-7">
        <h1 class="text-3xl font-bold text-center">Create Account</h1>

        <form class="space-y-5" @submit.prevent="handleRegister">
          
          <div>
            <label for="username" class="font-light text-sm block mb-1">Username</label>
            <input id="username" type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:border-gray-600 focus:outline-none"
              placeholder="Your username">
          </div>

          <div>
            <label for="email" class="font-light text-sm block mb-1">Email</label>
            <input id="email" type="email" class="w-full border border-gray-300 p-3 rounded-lg focus:border-gray-600 focus:outline-none"
              placeholder="Your email">
          </div>

          <div>
            <label for="password" class="font-light text-sm block mb-1">Password</label>
            <input id="password" type="password" class="w-full border border-gray-300 p-3 rounded-lg focus:border-gray-600 focus:outline-none"
              placeholder="••••••••••••••••">
          </div>

          <button type="submit" class="w-full py-4 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 focus:ring-2 focus:ring-primary-400">Sign Up</button>
        </form>
      </div>

      <div class="text-center mt-6">
        <h4 class="text-gray-500 font-light text-sm">Already have an account? 
          <NuxtLink to="/auth/login" class="text-black underline font-medium">Log in</NuxtLink>
        </h4>
      </div>
    </div>
  </div>
</template>

<script setup>
function handleRegister() {
  // Logic setelah register berhasil
  return navigateTo('/auth/login')
}
</script>