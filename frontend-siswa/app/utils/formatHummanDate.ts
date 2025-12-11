export default function (
  timestamp: string | number | Date,
  options?: { showTime?: boolean }
): string {
  const date = new Date(timestamp);

  if (isNaN(date.getTime())) {
    return "-";
  }

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
