const PUBLIC_ROUTES: (string | RegExp)[] = [/^\/auth/];

const RESTRICTED_PATHS: Record<UserRoleType, (string | RegExp)[]> = {
  // BK: Tidak ada batasan (akses semua)
  bk: [],

  // Guru: Tidak boleh akses manajemen user, master data, dan fitur khusus konseling
  guru: [
    /^\/users/,
    /^\/master-data/,
    /^\/counseling/
  ],

  // Staff: Tidak boleh akses manajemen user, konseling privasi, dan monitoring mood
  staff: [
    /^\/users/,
    /^\/counseling/,
    /^\/moods/
  ],
};

export default defineNuxtRouteMiddleware((to, from) => {
  if (import.meta.server) return;

  const auth = useAuthStore();
  const path = to.path;

  const isPublic = PUBLIC_ROUTES.some((route) =>
    route instanceof RegExp ? route.test(path) : path === route
  );

  // 1. Jika BELUM login dan akses halaman private -> Tendang ke login
  if (!auth.isAuthenticated && !isPublic) {
    return navigateTo("/auth/login");
  }

  // 2. Jika SUDAH login
  if (auth.isAuthenticated) {
    // A. Mencegah user login masuk kembali ke halaman auth (Login/Register)
    if (isPublic) {
      return navigateTo("/dashboard");
    }

    // B. Cek Hak Akses (RBAC)
    const role = auth.userRole || "guru";
    const restrictions = RESTRICTED_PATHS[role] || [];

    const isForbidden = restrictions.some((rule) =>
      rule instanceof RegExp ? rule.test(path) : path === rule
    );

    if (isForbidden) {
      return navigateTo("/dashboard");
    }
  }
});
