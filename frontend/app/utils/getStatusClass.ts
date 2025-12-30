const STATUS_CLASS: Record<string, string> = {
  pending: 'bg-amber-100 text-amber-700',
  scheduled: 'bg-primary-100 text-primary-700',
  completed: 'bg-emerald-100 text-emerald-700',
  rejected: 'bg-red-100 text-red-700',
};

export default function (status: string) {
  return STATUS_CLASS[status] ?? 'bg-gray-100 text-gray-700';
}
