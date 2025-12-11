// composables/useApi.ts
const createApiError = (status: number, message: string, data?: any) => {
  return createError({
    statusCode: status,
    statusMessage: message,
    fatal: false, // Tidak selalu fatal
    data: data,
  });
};

export function useApi(withAuth: boolean = true) {
  const config = useRuntimeConfig();
  const auth = withAuth ? useAuthStore() : null;

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

  async function get<T>(url: string) {
    return fetch<ApiResponse<T>>(url);
  }

  async function fetchFileBlob(url: string): Promise<Blob> {
    return fetch<Blob>(url, { responseType: "blob" });
  }

  async function post<T = any>(url: string, body: any) {
    return fetch<ApiResponse<T>>(url, {
      method: "POST",
      body,
    });
  }

  async function put<T>(url: string, body: any) {
    return fetch<ApiResponse<T>>(url, {
      method: "PUT",
      body,
    });
  }

  async function patch<T>(url: string, body: any) {
    return fetch<ApiResponse<T>>(url, {
      method: "PATCH",
      body,
    });
  }

  async function destroy<T = any>(url: string, body?: object) {
    return fetch<ApiResponse<T>>(url, {
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
