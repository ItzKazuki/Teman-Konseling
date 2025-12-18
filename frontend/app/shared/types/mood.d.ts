interface MoodData {
  stats: MoodStats
  mood_logs: MoodLog[]
  vulnerable_students: MoodLog[]
}

interface MoodStats {
  total_students: number
  filled_today: number
  participation_percentage: number
  avg_stress_level: number
  vulnerable_count: number
}

interface MoodLog {
  id: string
  student_id: string
  student_name: string
  student_avatar_url: string
  student_classroom: string
  emotion_name: string
  magnitude: number
  story: string
  is_custom: boolean
  created_at: string
  time_ago: string
}