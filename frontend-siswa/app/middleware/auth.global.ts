export default defineNuxtRouteMiddleware((to, from) => {
  if (import.meta.server) return;

  const auth = useAuthStore();

  // Daftar rute yang boleh diakses publik (tanpa login)
  const publicRoutes: (string | RegExp)[] = [
    '/', // Halaman utama (landing page)
    /^\/auth/ // Semua rute yang diawali dengan /auth (login, register, forgot password)
  ];

  const isPublicRoute = () => {
    return publicRoutes.some((route) =>
      route instanceof RegExp ? route.test(to.path) : to.path === route
    );
  };

  const expectedPath = '/home';
  
  if (auth.isAuthenticated) {
    if (isPublicRoute()) {
      return navigateTo(expectedPath);
    }
    return;
  }

  if (!auth.isAuthenticated) {
    if (!isPublicRoute()) {
      return navigateTo("/auth/login");
    }
    return;
  }
});