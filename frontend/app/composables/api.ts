// composables/useApi.ts
const createApiError = (status: number, message: string, data?: any) => {
  return createError({
    statusCode: status,
    statusMessage: message,
    fatal: false, // Tidak selalu fatal
    data: data,
  });
};

function buildQuery(params?: Record<string, any>) {
  if (!params) return '';

  const query = new URLSearchParams(
    Object.entries(params)
      .filter(([_, v]) => v !== null && v !== undefined && v !== '')
      .map(([k, v]) => [k, String(v)])
  ).toString();

  return query ? `?${query}` : '';
}

export function useApi(withAuth: boolean = true) {
  const config = useRuntimeConfig();
  const auth = withAuth ? useAuthStore() : null;

  // Fungsi internal untuk selipkan role prefix
  const withRole = (url: string, autoPrefix: boolean = true) => {
    // 1. Jika autoPrefix dimatikan secara manual, kembalikan URL asli
    if (!autoPrefix || !auth?.user?.role) return url;

    // 2. Daftar rute yang TIDAK boleh diberi prefix (Public/Common)
    // Contoh: /auth/login, /public/articles, atau master-data jika endpointnya global
    const excludedPaths = ['/auth', '/master-data', '/files', '/profile', '/dashboard-overview', '/reference']; 
    if (excludedPaths.some(p => url.startsWith(p))) return url;

    // 3. Jika URL sudah punya prefix role (mencegah double prefix)
    const prefixes = ['/admin', '/teacher', '/staff'];
    if (prefixes.some(p => url.startsWith(p))) return url;

    // 4. Tambahkan prefix berdasarkan role
    const roleMap: Record<string, string> = { bk: 'admin', guru: 'teacher', staff: 'staff' };
    const prefix = roleMap[auth?.user?.role] || 'public';
    
    return `/${prefix}${url.startsWith('/') ? url : '/' + url}`;
  };

  const baseURL = `${config.public.apiBase}/api/${config.public.apiVersion}`;
  const defaultHeaders = { Accept: "application/json" };

  const fetch = $fetch.create({
    baseURL,
    headers: defaultHeaders,
    responseType: "json",

    async onRequest({ options }) {
      if (withAuth && auth?.token) {
        const h = new Headers(options.headers as HeadersInit);
        h.set("Authorization", `Bearer ${auth.token}`);
        options.headers = h;
      }
    },

    async onRequestError({ error }) {
      console.error("Network/Request error:", error);

      // Melempar error dengan pesan generik untuk kegagalan koneksi/jaringan
      throw createApiError(
        503,
        "Tidak bisa terhubung ke server. Coba lagi nanti.",
        { message: "Tidak bisa terhubung ke server. Coba lagi nanti." }
      );
    },

    async onResponseError({ response }) {
      // Jika tidak ada response, anggap masalah koneksi/server down
      if (!response) {
        throw createApiError(
          503,
          "Server tidak merespons. Periksa koneksi Anda.",
          { message: "Server tidak merespons. Periksa koneksi Anda." }
        );
      }

      const status = response.status;
      const apiMessage = response._data?.message || "Terjadi kesalahan pada server.";

      switch (status) {
        case 401:
          if (withAuth && auth?.token) {
            useAlert().info('Sesi Berakhir, silahkan login kembali');
            auth.logout(false);
            navigateTo('/auth/login');
            return Promise.reject();
          }
          break;

        case 403:
          debug("Forbidden: " + apiMessage, 'error');
          break;

        case 422:
          debug("Validation error:" + JSON.stringify(response._data?.errors), 'error');
          break;

        case 500:
          debug("Server error: " + apiMessage, 'error');
          break;

        case 503:
          debug("Service unavailable: " + apiMessage, 'error');
          break;
      }

      throw createApiError(
        status,
        apiMessage,
        response._data
      );
    },
  });

  const rawFetch = $fetch.create({
    baseURL,
    headers: defaultHeaders,
    responseType: "json",
  });

  async function get<T>(url: string, options?: GetOptions) {
    const query = buildQuery(options?.params);

    return fetch<ApiResponse<T>>(`${withRole(url)}${query}`);
  }

  async function fetchFileBlob(url: string): Promise<Blob> {
    return fetch<Blob>(url, { responseType: "blob" });
  }

  async function post<T = any>(url: string, body?: any) {
    return fetch<ApiResponse<T>>(withRole(url), {
      method: "POST",
      body,
    });
  }

  async function put<T>(url: string, body: any) {
    return fetch<ApiResponse<T>>(withRole(url), {
      method: "PUT",
      body,
    });
  }

  async function patch<T>(url: string, body: any) {
    return fetch<ApiResponse<T>>(withRole(url), {
      method: "PATCH",
      body,
    });
  }

  async function destroy<T = any>(url: string, body?: object) {
    return fetch<ApiResponse<T>>(withRole(url), {
      method: "DELETE",
      body,
    });
  }

  return {
    fetch,
    rawFetch,
    get,
    post,
    put,
    patch,
    destroy,
    fetchFileBlob,
  };
}
