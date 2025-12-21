interface CounselingRequest {
  id: string
  title: string
  description: string
  urgency: string
  status: string
  student: Student
  schedule: CounselingSchedule
  created_at: string
  updated_at: string
}

interface CounselingSchedule {
  id: string
  request_id: string
  counselor_id: string
  method: string
  schedule_date: string
  time_slot: string
  status: string
  confirmed_at: any
  counselor: Counselor
}
