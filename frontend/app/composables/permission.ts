export const usePermission = () => {
  const auth = useAuthStore();

  /**
   * Fungsi untuk mengecek apakah user memiliki role yang diizinkan
   * @param allowedRoles - Array role yang boleh mengakses (contoh: ['bk', 'guru'])
   * @returns boolean
   */
  const can = (allowedRoles: string | string[]) => {
    // Jika user belum login atau role tidak ada
    if (!auth.isAuthenticated || !auth.user?.role) return false;

    // Jika yang dimasukkan adalah string tunggal, ubah jadi array
    const rolesArray = Array.isArray(allowedRoles) ? allowedRoles : [allowedRoles];

    // Cek apakah role user saat ini ada di dalam daftar role yang diizinkan
    return rolesArray.includes(auth.user?.role);
  };

  /**
   * Fungsi helper jika butuh pengecekan khusus BK (Admin)
   */
  const isBk = () => auth.user?.role === 'bk';

  return {
    can,
    isBk
  };
};