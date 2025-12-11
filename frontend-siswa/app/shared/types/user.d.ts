type UserRoleType = 'admin' | 'guru' | 'siswa';

interface User {
  id?: string;
  nip: string;
  name: string;
  role?: UserRoleType;
  avatar_url?: string;
  avatar_file_id?: string;
  email: string;
  password?: string;
  password_confirmation?: string;
}

interface Student {
  id?: string;
  nisn: string;
  nis: string;
  name: string;
  role?: UserRoleType;
  avatar_url?: string;
  avatar_file_id?: string;
  email: string;
  password?: string;
  password_confirmation?: string;
}