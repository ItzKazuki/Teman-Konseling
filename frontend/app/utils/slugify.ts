export default function(text: string): string {
  // 1. Ubah ke huruf kecil
  let slug = text.toLowerCase();
  
  // 2. Ganti karakter non-word (termasuk spasi) dan non-hyphen menjadi tanda hubung
  // Pola RegEx: /[\s\W-]+/g (spasi, karakter non-word, atau tanda hubung berulang)
  slug = slug.replace(/[\s\W-]+/g, '-');
  
  // 3. Hapus tanda hubung dari awal atau akhir string
  slug = slug.replace(/^-+|-+$/g, '');

  return slug;
};