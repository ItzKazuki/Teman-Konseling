export default function(isoDate: string | null): string | null {
  if (!isoDate) return null;

  try {
    const date = new Date(isoDate);

    // Menggunakan Intl untuk mendapatkan bagian-bagian tanggal dalam zona waktu spesifik
    const formatter = new Intl.DateTimeFormat('en-GB', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
      timeZone: 'Asia/Jakarta',
    });

    const parts = formatter.formatToParts(date);
    const getPart = (type: string) => parts.find(p => p.type === type)?.value;

    const yyyy = getPart('year');
    const mm = getPart('month');
    const dd = getPart('day');
    const hh = getPart('hour');
    const min = getPart('minute');

    // Menghasilkan format YYYY-MM-DDTHH:mm
    return `${yyyy}-${mm}-${dd}T${hh}:${min}`;
  } catch (e) {
    console.error("Format error:", e);
    return null;
  }
}