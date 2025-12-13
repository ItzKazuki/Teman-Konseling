export default function (
  timestamp: string | number | Date | undefined | null,
  options?: { showTime?: boolean }
): string {
  if(!timestamp) return '-';

  const date = new Date(timestamp);

  if (isNaN(date.getTime())) return "-";

  const formattedDate = date.toLocaleDateString(
    useRuntimeConfig().public.appLocale,
    {
      day: "numeric",
      month: "long",
      year: "numeric",
    }
  );

  if (options?.showTime) {
    const formattedTime = date.toLocaleTimeString(
      useRuntimeConfig().public.appLocale,
      {
        hour: "2-digit",
        minute: "2-digit",
      }
    );
    return `${formattedDate}, ${formattedTime}`;
  }

  return formattedDate;
}
