<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MoodResource;
use App\Models\DailyMood;
use App\Models\Student;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Data Mood Siswa', weight: 3)]
class MoodController extends Controller
{
    public function index()
    {
        $today = now()->today();

        // 1. Statistik
        $totalStudents = Student::count();
        $moodsToday = DailyMood::whereDate('created_at', $today)->get();
        $filledTodayCount = $moodsToday->count();

        $participationPercentage = $totalStudents > 0
            ? round(($filledTodayCount / $totalStudents) * 100)
            : 0;

        $stats = [
            'total_students' => $totalStudents,
            'filled_today' => $filledTodayCount,
            'participation_percentage' => $participationPercentage,
            'avg_stress_level' => round($moodsToday->avg('magnitude'), 1) ?: 0,
            'vulnerable_count' => $moodsToday->where('magnitude', 4)->count(),
        ];

        // 2. Log Terbaru (dengan Relasi Student)
        $moodLogs = DailyMood::with('student.classroom')
            ->latest()
            ->take(10)
            ->get();

        // 3. Siswa Rentan
        $vulnerable = DailyMood::with('student.classroom')
            ->whereIn('emotion_name', DailyMood::getNegativeEmotions())
            ->where('magnitude', 4)
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        return ApiResponse::success([
            'stats' => $stats,
            'mood_logs' => MoodResource::collection($moodLogs),
            'vulnerable_students' => MoodResource::collection($vulnerable), // Buat resource jika perlu
        ]);
    }

    public function history(Request $request)
    {
        $query = DailyMood::with('student.classroom');

        // Filter Nama/NISN
        if ($request->search) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('nisn', 'like', "%{$request->search}%");
            });
        }

        // Filter Kelas
        if ($request->classroom) {
            $query->whereHas('student.classroom', function ($q) use ($request) {
                $q->where('name', $request->classroom);
            });
        }

        // Filter Rentang Tanggal
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        return MoodResource::collection($query->latest()->paginate(20));
    }
}
