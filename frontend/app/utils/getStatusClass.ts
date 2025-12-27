const STATUS_CLASS: Record<string, string> = {
  scheduled: 'bg-indigo-100 text-indigo-700',
  pending: 'bg-amber-100 text-amber-700',
  completed: 'bg-emerald-100 text-emerald-700',
  rejected: 'bg-red-100 text-red-700',
};

export default function (status: string) {
  return STATUS_CLASS[status] ?? 'bg-gray-100 text-gray-700';
}
