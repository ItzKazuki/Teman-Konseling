interface DashboardOverview {
  summary_stats: SummaryStats
  happiness_index: HappinessIndex
  urgent_interventions: any[]
  counseling_status: CounselingStatus
  recent_requests: RecentRequest[]
}

interface SummaryStats {
  total_students: TotalStudents
  mood_entries: MoodEntries
  active_cases: ActiveCases
  vulnerable_students: VulnerableStudents
}

interface TotalStudents {
  value: number
  trend: string
  percentage: number
}

interface MoodEntries {
  value: number
  trend: string
  percentage: number
}

interface ActiveCases {
  value: number
  trend: string
  percentage: number
}

interface VulnerableStudents {
  value: number
  trend: string
  percentage: number
}

interface HappinessIndex {
  labels: string[]
  values: number[]
}

interface CounselingStatus {
  completed: number
  in_progress: number
  pending: number
}

interface RecentRequest {
  id: string
  student: string
  class: string
  title: string
  urgency: string
  status: string
}