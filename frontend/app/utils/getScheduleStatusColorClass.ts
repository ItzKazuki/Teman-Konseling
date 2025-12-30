export default function (status: string, isActive: boolean) {
  if (!isActive) return 'bg-gray-50 text-gray-400 border-gray-200 hover:border-gray-300';

  const styles: Record<string, string> = {
    pending: 'bg-amber-500 text-white border-amber-500 shadow-md ring-2 ring-amber-100',
    confirmed: 'bg-primary-600 text-white border-primary-600 shadow-md ring-2 ring-primary-100',
    canceled: 'bg-rose-600 text-white border-rose-600 shadow-md ring-2 ring-rose-100',
    completed: 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-100',
  };

  return styles[status] || 'bg-gray-600 text-white border-gray-600';
}