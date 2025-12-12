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