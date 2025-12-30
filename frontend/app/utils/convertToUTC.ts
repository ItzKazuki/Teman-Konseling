export default function (localDateTime: string | null): string | null {
  if (!localDateTime) return null;

  const date = new Date(localDateTime);

  // toISOString() otomatis mengubah waktu lokal tersebut ke UTC 
  return date.toISOString();
}