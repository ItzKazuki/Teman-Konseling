// 1. State global (Singleton)
let currentId = 0;
const toasts = ref<ToastItem[]>([]);

/**
 * Fungsi utama untuk menampilkan toast.
 * Menggunakan ID unik dan mengatur timeout yang spesifik.
 */
function showToast(options: Omit<ToastItem, 'id'>) {
  const id = ++currentId;
  const duration = options.duration ?? 5000; // Default 5 detik

  const newToast: ToastItem = {
    ...options,
    id,
    duration,
  };

  toasts.value.push(newToast);

  // Atur timeout hanya jika duration > 0
  if (duration > 0) {
    setTimeout(() => {
      removeToast(id);
    }, duration);
  }
}

/**
 * Menghapus toast berdasarkan ID uniknya.
 * Dipanggil oleh timeout atau tombol tutup di komponen Toast.
 */
function removeToast(id: number | number) {
  const index = toasts.value.findIndex(toast => toast.id === id);
  if (index !== -1) {
    toasts.value.splice(index, 1);
  }
}

/**
 * Composables useToast
 * Menyediakan antarmuka publik untuk mengelola notifikasi toast.
 */
export function useToast() {
  function success(text: string, duration?: number) {
    showToast({ type: "success", text, duration });
  }

  function warning(text: string, duration?: number) {
    showToast({ type: "warning", text, duration });
  }

  function error(text: string, duration?: number) {
    showToast({ type: "error", text, duration });
  }

  function info(text: string, duration?: number) {
    showToast({ type: "info", text, duration });
  }

  // Mengembalikan toasts sebagai readonly untuk mencegah modifikasi langsung
  return {
    toasts: readonly(toasts),
    show: showToast,
    remove: removeToast,
    success,
    warning,
    error,
    info,
  };
}