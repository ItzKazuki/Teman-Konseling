/**
 * Composable untuk mengelola dan mengakses error validasi formulir 
 * yang biasanya berasal dari respons API (misalnya, HTTP 422).
 * * @returns {UseFormErrors} Objek dengan state error dan utility functions.
 */
export function useFormErrors(): UseFormErrors {
  // Menggunakan interface FormErrors untuk typing yang jelas
  const formErrors = ref<FormErrors>({});

  // --- Fungsi Mutasi ---

  function setErrors(errors: FormErrors): void {
    formErrors.value = errors;
  }

  function clearErrors(): void {
    formErrors.value = {};
  }

  // --- Computed Properties & Utilities ---

  /**
   * Mengecek apakah ada error sama sekali dalam formulir.
   */
  const hasErrors = computed<boolean>(() => {
    return Object.keys(formErrors.value).length > 0;
  });

  /**
   * Mengecek apakah field tertentu memiliki error.
   * @param field Nama field yang ingin diperiksa.
   */
  function has(field: string): boolean {
    return !!formErrors.value[field] && formErrors.value[field].length > 0;
  }

  /**
   * Mengambil pesan error pertama untuk field tertentu.
   * @param field Nama field yang ingin diambil error-nya.
   * @returns Pesan error pertama (string) atau null jika tidak ada.
   */
  function first(field: string): string | null {
    return formErrors.value[field]?.[0] || null;
  }

  return {
    formErrors,
    hasErrors,
    setErrors,
    clearErrors,
    has,
    first,
  };
}
