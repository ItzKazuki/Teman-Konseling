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
}

interface Student {
  id?: string;
  nis: string;
  nisn: string;
  name: string;
  avatar_url?: string;
  email: string;
  password?: string;
  class_room_id: string;
  classroom_name?: string;
  password_confirmation?: string;
  created_at?: string;
  updated_at?: string;
}