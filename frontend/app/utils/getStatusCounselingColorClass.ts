export default function (status: string, isActive: boolean) {
  if (!isActive) return 'bg-white text-gray-400 border-gray-100 hover:border-gray-300';

  const activeStyles: Record<string, string> = {
    pending: 'bg-amber-500 text-white border-amber-500 shadow-md ring-2 ring-amber-100',
    scheduled: 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-100',
    completed: 'bg-emerald-600 text-white border-emerald-600 ring-2 ring-emerald-100',
    rejected: 'bg-rose-600 text-white border-rose-600 ring-2 ring-rose-100',
  };

  return activeStyles[status] || 'bg-gray-600 text-white border-gray-600 ring-2 ring-gray-100';
}