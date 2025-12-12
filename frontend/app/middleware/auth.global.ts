export default defineNuxtRouteMiddleware((to, from) => {
  if (import.meta.server) return;

  const auth = useAuthStore();

  const publicRoutes: (string | RegExp)[] = [/^\/auth/];

  const isPublicRoute = () => {
    return publicRoutes.some((route) =>
      route instanceof RegExp ? route.test(to.path) : to.path === route
    );
  };

  const restrictedPaths: Record<UserRoleType, (string | RegExp)[]> = {
    guru: [/^\/dashboard\/admin/],
    bk: [/^\/dashboard\/leader/],
  };

  if (!auth.isAuthenticated && !isPublicRoute()) {
    return navigateTo("/auth/login");
  }

  if (auth.isAuthenticated) {
    const role = auth.userRole ?? "guru";
    const expectedPath = '/dashboard';

    const forbidden = restrictedPaths[role].some((rule) =>
      rule instanceof RegExp ? rule.test(to.path) : to.path === rule
    );

    if (forbidden) {
      return navigateTo(expectedPath);
    }
  }
});
