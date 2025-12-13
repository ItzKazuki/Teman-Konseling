/**
 * Interface standar untuk error validasi, di mana key adalah nama field, 
 * dan value adalah array dari pesan error (string[]).
 */
interface FormErrors {
  [key: string]: string[];
}

/**
 * Interface untuk hasil return dari useFormErrors.
 */
interface UseFormErrors {
  formErrors: Ref<FormErrors>;
  hasErrors: ComputedRef<boolean>;
  setErrors: (errors: FormErrors) => void;
  clearErrors: () => void;
  first: (field: string) => string | null;
  has: (field: string) => boolean;
}