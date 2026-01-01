<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CounselingResource;
use App\Models\RequestCounseling;
use App\Models\ScheduleCounseling;
use App\Notifications\Bk\ReminderCounselingStudentNotification;
use App\Notifications\Student\CounselingStatusUpdated;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

#[Group('Admin: Konseling', weight: 3)]
class CounselingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = RequestCounseling::query();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $counselings = $query->with([
            'schedule.counselor',
            'student.classroom',
        ])
            ->orderByRaw("FIELD(status, 'scheduled', 'pending', 'completed', 'rejected')")
            ->orderByRaw("FIELD(urgency, 'high', 'medium', 'low')")
            ->paginate($perPage);

        $counselings->getCollection()->transform(function ($counseling) {
            return new CounselingResource($counseling);
        });

        return ApiResponse::success($counselings);
    }

    public function summary()
    {
        $highUrgency = RequestCounseling::where('urgency', 'high')
            ->whereNotIn('status', ['rejected', 'completed'])
            ->count();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $scheduledThisWeek = RequestCounseling::whereHas('schedule', function ($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        })->where('status', 'scheduled')->count();

        $pendingConfirmation = RequestCounseling::where('status', 'pending')->count();

        return ApiResponse::success([
            'highUrgency' => $highUrgency,
            'scheduled' => $scheduledThisWeek,
            'pending' => $pendingConfirmation,
        ], 'Berhasil mengambil ringkasan data');
    }

    public function show(string $id)
    {
        $requestCounseling = RequestCounseling::where('id', $id)
            ->with(['schedule.counselor', 'student.classroom'])
            ->first();

        if (! $requestCounseling) {
            return ApiResponse::error('Counseling request not found.', 404);
        }

        return ApiResponse::success(new CounselingResource($requestCounseling));
    }

    public function update(Request $request, string $id)
    {
        // 1. Cari data dengan relasinya
        $requestCounseling = RequestCounseling::with(['schedule'])->find($id);

        if (! $requestCounseling) {
            return ApiResponse::error('Counseling request not found.', 404);
        }

        // 2. Validasi input
        $validator = Validator::make($request->all(), [
            // Status untuk tabel RequestCounseling
            'request_status' => 'required|in:pending,scheduled,rejected,completed',
            // Status untuk tabel ScheduleCounseling
            'schedule_status' => 'required|in:pending,confirmed,canceled,completed',

            'notes' => 'nullable|string',
            'schedule_method' => 'required|in:chat,face-to-face',
            'schedule_date' => 'required|date',
            'time_slot' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', 422, $validator->errors());
        }

        DB::beginTransaction();

        try {
            // 3. Update RequestCounseling (Status & Deskripsi/Catatan)
            $requestCounseling->update([
                'status' => $request->request_status, // Menggunakan request_status
                'notes' => $request->notes,
            ]);

            // 4. Update ScheduleCounseling (Status Spesifik Pertemuan)
            if ($requestCounseling->schedule) {
                $requestCounseling->schedule->update([
                    'method' => $request->schedule_method,
                    'schedule_date' => $request->schedule_date,
                    'time_slot' => $request->time_slot,
                    'status' => $request->schedule_status, // Menggunakan schedule_status

                    // Logika confirmed_at otomatis jika status diubah ke confirmed
                    'confirmed_at' => ($request->schedule_status === 'confirmed' && ! $requestCounseling->schedule->confirmed_at)
                                       ? now() : $requestCounseling->schedule->confirmed_at,
                ]);
            }

            if ($request->schedule_status === 'completed') {
                $requestCounseling->update([
                    'status' => 'completed',
                ]);
            }

            DB::commit();

            $student = $requestCounseling->student;

            if ($student) {
                $student->notify(
                    new CounselingStatusUpdated(
                        $requestCounseling,
                        $request->schedule_status,
                    )
                );
            }

            return ApiResponse::success($requestCounseling->load('schedule'), 'Counseling updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::error('Failed to update counseling: '.$e->getMessage(), 500);
        }
    }

    public function sendReminder(string $id)
    {
        $schedule = ScheduleCounseling::with('request.student')->findOrFail($id);

        $student = $schedule->request->student;

        $student->notify(
            new ReminderCounselingStudentNotification($schedule)
        );

        return ApiResponse::success(
            message: 'Reminder berhasil dikirim.'
        );
    }
}
