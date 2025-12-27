<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\DailyMood;
use App\Models\RequestCounseling;
use App\Models\ScheduleCounseling;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function overview()
    {
        try {
            // 1. Summary Stats
            $totalStudents = Student::count();
            $moodEntriesToday = DailyMood::whereDate('created_at', Carbon::today())->count();
            $activeCases = ScheduleCounseling::whereIn('status', ['pending', 'confirmed'])->count();

            $vulnerableCount = DailyMood::whereIn('emotion_name', DailyMood::getNegativeEmotions())
                ->where('magnitude', 4)
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->distinct('student_id')
                ->count('student_id');

            // 2. Happiness Index (7 Hari Terakhir)
            $labels = [];
            $happinessValues = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->isoFormat('ddd'); // Sen, Sel, Rab...

                $avgMood = DailyMood::whereDate('created_at', $date->toDateString())->avg('magnitude');
                $happinessValues[] = $avgMood ? round($avgMood * 20, 1) : 0; // Skala 1-5 dikali 20 jadi 1-100
            }

            // 3. Intervensi Mendesak (Data Siswa dengan DailyMood Sangat Rendah)
            $urgentInterventions = DailyMood::with('student')
                ->where('magnitude', '<=', 2)
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($mood) {
                    return [
                        'id' => $mood->student->id,
                        'name' => $mood->student->name,
                        'reason' => 'DailyMood Level '.$mood->magnitude.': '.($mood->note ?? 'Tanpa catatan'),
                    ];
                });

            // 4. Status Konseling
            $counselingStats = ScheduleCounseling::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            // 5. Permintaan Konseling Terbaru
            $recentRequests = RequestCounseling::with(['student.classRoom'])
                ->whereIn('status', ['scheduled', 'pending'])
                ->orderByRaw("FIELD(urgency, 'high', 'medium', 'low')")
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($req) => [
                    'id' => $req->id,
                    'student' => $req->student->name ?? 'Unknown',
                    'class' => $req->student->classRoom->name ?? 'N/A',
                    'title' => $req->title,
                    'urgency' => $req->urgency,
                    'status' => $req->status,
                ]);

            return ApiResponse::success([
                'summary_stats' => [
                    'total_students' => ['value' => $totalStudents, 'trend' => 'up', 'percentage' => 0], // Trend bisa dihitung manual jika ada data historis
                    'mood_entries' => ['value' => $moodEntriesToday, 'trend' => 'up', 'percentage' => 0],
                    'active_cases' => ['value' => $activeCases, 'trend' => 'down', 'percentage' => 0],
                    'vulnerable_students' => ['value' => $vulnerableCount, 'trend' => 'stable', 'percentage' => 0],
                ],
                'happiness_index' => [
                    'labels' => $labels,
                    'values' => $happinessValues,
                ],
                'urgent_interventions' => $urgentInterventions,
                'counseling_status' => [
                    'completed' => $counselingStats['completed'] ?? 0,
                    'in_progress' => $counselingStats['confirmed'] ?? 0,
                    'pending' => $counselingStats['pending'] ?? 0,
                ],
                'recent_requests' => $recentRequests,
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
