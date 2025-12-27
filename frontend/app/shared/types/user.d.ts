type UserRoleType = 'bk' | 'guru';

interface User {
  id?: string;
  nip: string;
  name: string;
  role?: UserRoleType;
  jabatan?: string;
  avatar_url?: string;
  avatar_file_id?: string;
  email: string;
  password?: string;
  password_confirmation?: string;
}

interface Teacher {
  id: string;
  name: string;
  avatar_url: string
}

interface Student {
  id?: string;
  nis: string;
  nisn: string;
  name: string;
  avatar_url?: string;
  email: string;
  phone: string;
  password?: string;
  class_room_id: string;
  classroom_name?: string;
  password_confirmation?: string;
  created_at?: string;
  updated_at?: string;
}

interface Counselor {
  id: string
  name: string
  jabatan: string
  is_available: boolean
  avatar_url: string
}

interface StudentDetail {
  name: string
  avatar_url?: string;
  nisn: string
  phone: string;
  address: string;
  parent_name: string;
  parent_phone: string;
  classroom: string
  mood_history: MoodHistory[]
  chart_labels: string[]
  chart_data: number[]
}

interface MoodHistory {
  id: string
  emotion_name: string
  magnitude: number
  story: string
  formatted_date: string
}