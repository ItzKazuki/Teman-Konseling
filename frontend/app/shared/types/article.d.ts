type ArticleStatus = 'draft' | 'published' | 'archived';

interface Article {
  id?: string;
  author_id: string;
  article_category_id: string;
  thumbnail_file_id: string;
  thumbnail_url?: string;
  author_name?: string;
  category_name?: string;
  title: string;
  slug?: string;
  excerpt: string;
  content: string;
  status: ArticleStatus;
  views?: number; // Menggunakan 'number' untuk integer
  published_at?: string | null; // Nullable string (tanggal-waktu)
  created_at?: string | null; // Nullable string (ISO 8601 date-time)
  updated_at?: string | null; // Nullable string (ISO 8601 date-time)
}

interface ArticleCategory {
  id: string;
  slug?: string;
  name: string;
  description: string;
  created_at?: string | null; // Nullable string (ISO 8601 date-time)
  updated_at?: string | null; // Nullable string (ISO 8601 date-time)
}