<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MoodResource;
use App\Models\DailyMood;
use App\Models\Student;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Teacher: Mood', weight: 4)]
class MoodController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->today();
        $user = $request->user();

        $classIds = $user->classrooms()->pluck('id');

        // --- Query Dasar ---
        $studentQuery = Student::query();
        $moodQuery = DailyMood::whereDate('created_at', $today);

        $studentQuery->whereIn('class_room_id', $classIds);
        $moodQuery->whereHas('student', fn ($q) => $q->whereIn('class_room_id', $classIds));

        $totalStudents = $studentQuery->count();
        $moodsToday = $moodQuery->get();
        $filledTodayCount = $moodsToday->count();

        $stats = [
            'total_students' => $totalStudents,
            'filled_today' => $filledTodayCount,
            'participation_percentage' => $totalStudents > 0 ? round(($filledTodayCount / $totalStudents) * 100) : 0,
            'avg_stress_level' => round($moodsToday->avg('magnitude'), 1) ?: 0,
            'vulnerable_count' => $moodsToday->where('magnitude', 4)->count(),
        ];

        $moodLogs = [];
        $vulnerable = [];

        if (in_array($user->role, ['bk', 'guru'])) {
            $moodLogs = (clone $moodQuery)->with('student.classroom')->latest()->take(10)->get();

            $vulnerable = (clone $moodQuery)->with('student.classroom')
                ->whereIn('emotion_name', DailyMood::getNegativeEmotions())
                ->where('magnitude', 4)
                ->latest()
                ->get();
        }

        return ApiResponse::success([
            'stats' => $stats,
            'mood_logs' => MoodResource::collection($moodLogs),
            'vulnerable_students' => MoodResource::collection($vulnerable),
        ]);
    }
}
