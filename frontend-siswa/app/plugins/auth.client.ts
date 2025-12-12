export default defineNuxtPlugin(async () => {
  const auth = useAuthStore();

  auth.loadTokenFromCookie();

  if (auth.token && !auth.user) {
    try {
      const res = await useApi().get<User>('/auth/student/me');
      if (!res.status) throw new Error('Sesi Berakhir, silahkan login kembali')
      auth.setUser(res.data)
    } catch (e: any) {
      debug('Session Expired', 'error');
    }
  }
})
