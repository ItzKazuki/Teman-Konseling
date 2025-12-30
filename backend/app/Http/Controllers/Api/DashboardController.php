<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\DailyMood;
use App\Models\RequestCounseling;
use App\Models\ScheduleCounseling;
use App\Models\Student;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Group('Dashboard Pengguna', weight: 2)]
class DashboardController extends Controller
{
    public function overview(Request $request)
    {
        $user = $request->user();
        $role = $user->role;
        $today = Carbon::today();

        $studentScope = Student::query();

        if ($role === Role::GURU) {
            // Guru hanya melihat siswa di kelas yang dia ajar
            $classIds = $user->classrooms()->pluck('id');
            $studentScope->whereIn('class_room_id', $classIds);
        }

        $allowedStudentIds = $studentScope->pluck('id');

        $totalStudents = $studentScope->count();

        $moodEntriesToday = DailyMood::whereIn('student_id', $allowedStudentIds)
            ->whereDate('created_at', $today)->count();

        $activeCases = ScheduleCounseling::whereHas('request', function ($query) use ($allowedStudentIds) {
            $query->whereIn('student_id', $allowedStudentIds);
        })->whereIn('status', ['pending', 'confirmed'])->count();

        $vulnerableCount = DailyMood::whereIn('student_id', $allowedStudentIds)
            ->whereIn('emotion_name', DailyMood::getNegativeEmotions())
            ->where('magnitude', 4)
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->distinct('student_id')
            ->count('student_id');

        $labels = [];
        $happinessValues = [];

        // Sembunyikan chart jika staff (Opsional, tergantung kebijakan Anda)
        if ($role !== Role::STAFF) {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->isoFormat('ddd');

                $avgMood = DailyMood::whereIn('student_id', $allowedStudentIds)
                    ->whereDate('created_at', $date->toDateString())
                    ->avg('magnitude');
                $happinessValues[] = $avgMood ? round($avgMood * 20, 1) : 0;
            }
        }

        $urgentInterventions = [];
        if (in_array($role, ['bk', 'guru'])) {
            $urgentInterventions = DailyMood::with('student')
                ->whereIn('student_id', $allowedStudentIds)
                ->where('magnitude', '<=', 2)
                ->latest()->take(5)->get()
                ->map(fn ($mood) => [
                    'id' => $mood->student->id,
                    'name' => $mood->student->name,
                    'reason' => "Mood Level {$mood->magnitude}: ".($mood->note ?? 'Tanpa catatan'),
                ]);
        }

        $counselingStatus = null;
        $recentRequests = null;

        if ($role === ROLE::BK) {
            $counselingStats = ScheduleCounseling::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->pluck('total', 'status');

            $counselingStatus = [
                'completed' => $counselingStats['completed'] ?? 0,
                'in_progress' => $counselingStats['confirmed'] ?? 0,
                'pending' => $counselingStats['pending'] ?? 0,
            ];

            $recentRequests = RequestCounseling::with(['student.classRoom'])
                ->whereIn('status', ['scheduled', 'pending'])
                ->orderByRaw("FIELD(urgency, 'high', 'medium', 'low')")
                ->latest()->limit(5)->get()
                ->map(fn ($req) => [
                    'id' => $req->id,
                    'student' => $req->student->name ?? 'Unknown',
                    'class' => $req->student->classRoom->name ?? 'N/A',
                    'title' => $req->title,
                    'urgency' => $req->urgency,
                    'status' => $req->status,
                ]);
        }

        return ApiResponse::success([
            'summary_stats' => [
                'total_students' => ['value' => $totalStudents, 'trend' => 'up', 'percentage' => 0],
                'mood_entries' => ['value' => $moodEntriesToday, 'trend' => 'up', 'percentage' => 0],
                'active_cases' => ['value' => $activeCases, 'trend' => 'down', 'percentage' => 0],
                'vulnerable_students' => ['value' => $vulnerableCount, 'trend' => 'stable', 'percentage' => 0],
            ],
            'happiness_index' => [
                'labels' => $labels,
                'values' => $happinessValues,
            ],
            'urgent_interventions' => $urgentInterventions,
            'counseling_status' => $counselingStatus,
            'recent_requests' => $recentRequests,
        ]);
    }
}
