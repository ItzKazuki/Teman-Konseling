import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: false },
  css: ["~/assets/css/main.css"],
  
  ssr: false,

  vite: {
    plugins: [tailwindcss()],
  },

  // DON'T CHANGE THIS VALUE, CHANGE USING .env
  devServer: {
    host: process.env.NUXT_APP_HOST ?? 'localhost',
    port: process.env.NUXT_APP_PORT ? Number(process.env.NUXT_APP_PORT) : 3000,
  },

  runtimeConfig: {
    appEnv: process.env.APP_ENV || "production",
    debug: process.env.APP_ENV !== "production",

    public: {
      apiBase: process.env.NUXT_PUBLIC_API_URL ?? "http://localhost:8000",
      apiVersion: process.env.NUXT_PUBLIC_API_VERSION ?? "v1",
      appLocale: process.env.NUXT_APP_LOCALE ?? "id-ID",
      appTimezone: process.env.NUXT_APP_TIMEZONE ?? "Asia/Jakarta",

      buildVersion: process.env.BUILD_VERSION || 'dev'
    },
  },

  app: {
    head: {
      title: process.env.NUXT_APP_NAME,
    },
  },

  icon: {
    mode: "css",
    cssLayer: "base",

    // for custom icon with custom prefix
    customCollections: [
      {
        prefix: 'tk', 
        dir: './app/assets/teman-konseling-icons',
        recursive: true, // if you want to include all the icons in nested directories enable recursive
      },
    ],
  },

  modules: ["@pinia/nuxt", "@nuxt/icon", "@nuxt/content"],
});