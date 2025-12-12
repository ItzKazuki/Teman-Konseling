export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: null as string | null,
    user: null as User | null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(body: LoginRequest) {
      try {
        const res = await useApi(false).post<Login>("/auth/student/login", body);

        if (res.status) {
          this.setToken(res.data.token);
          this.setUser(res.data.user);
        } else {
          throw new Error(res.message);
        }
      } catch (err: any) {
        throw err;
      }
    },

    async logout(fetch: boolean = true) {
      try {
        if (fetch) {
          const { message } = await useApi().rawFetch<ApiResponse>(
            "/auth/logout",
            {
              method: "POST",
              headers: {
                Authorization: this.token ? `Bearer ${this.token}` : "",
              },
            }
          );

          return message;
        }

        return 'Success Logout';
      } catch (err) {
        console.warn("Logout failed:", err);
      } finally {
        this.setToken(null)
        this.setUser(null)
      }
    },

    loadTokenFromCookie() {
      const tokenCookie = useCookie<string | null>("auth_token")
      this.token = tokenCookie.value || null
    },

    setToken(token: string | null) {
      const tokenCookie = useCookie<string | null>("auth_token");

      this.token = token;
      tokenCookie.value = token;
    },

    setUser(user: User | null) {
      this.user = user;
    },
  },
});
