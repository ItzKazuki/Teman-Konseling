const URGENCY_CLASS: Record<string, string> = {
  high: 'bg-red-100 text-red-700',
  medium: 'bg-orange-100 text-orange-700',
  low: 'bg-slate-100 text-slate-700',
};

export default function (urgency: string) {
  return URGENCY_CLASS[urgency] ?? 'bg-slate-100 text-slate-700';
}
