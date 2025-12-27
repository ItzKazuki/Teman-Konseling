<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MoodRequest;
use App\Http\Resources\MoodResource;
use App\Models\DailyMood;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\Group;

#[Group('Student: Daily Mood', weight: 3)]
class MoodController extends Controller
{
    public function store(MoodRequest $request)
    {
        // 1. Ambil data siswa yang sedang login
        $student = Auth::guard('student')->user();

        if (! $student) {
            return ApiResponse::error('Data siswa tidak ditemukan.', 404);
        }

        // 2. Validasi: Siswa hanya boleh isi 1x sehari
        $hasFilledToday = DailyMood::where('student_id', $student->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($hasFilledToday) {
            return ApiResponse::error('Kamu sudah mengisi emosi hari ini. Sampai jumpa besok!', 422);
        }

        // 3. Simpan data
        $mood = DailyMood::create([
            'student_id' => $student->id,
            'emotion_name' => $request->emotion_name,
            'magnitude' => $request->magnitude,
            'story' => $request->story,
            'is_custom' => $request->is_custom,
        ]);

        return ApiResponse::success(new MoodResource($mood), 'Emosi kamu berhasil dicatat. Terima kasih sudah berbagi!');
    }

    /**
     * Opsional: Mengecek status apakah sudah isi hari ini
     */
    public function checkStatus()
    {
        $student = Auth::guard('student')->user();
        $hasFilledToday = DailyMood::where('student_id', $student->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        return ApiResponse::success([
            'has_filled_today' => $hasFilledToday,
        ]);
    }
}
