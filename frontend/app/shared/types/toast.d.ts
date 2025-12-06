type ToastType = 'info' | 'success' | 'warning' | 'error' | 'default';

type VariantKey = Exclude<ToastType, undefined> | 'default';

interface ToastItem {
  id: number;
  type: ToastType;
  text: string;
  closable?: boolean;
  duration?: number;
}