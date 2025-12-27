interface Classroom {
  id?: string;
  name: string;
  description: string;
  level: number;
  homeroom_teacher: string;
  homeroom_teacher_name?: string;
  created_at?: string;
  updated_at?: string;
}

interface MasterDataClassroom {
  id: string;
  name: string;
}