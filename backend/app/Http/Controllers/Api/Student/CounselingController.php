<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CounselingFormRequest;
use App\Http\Requests\CounselingScheduleRequest;
use App\Http\Resources\CounselingResource;
use App\Http\Resources\ScheduleCounselingResource;
use App\Models\RequestCounseling;
use App\Models\ScheduleCounseling;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;

#[Group('Student: Counseling', weight: 3)]
class CounselingController extends Controller
{
    /**
     * Menampilkan daftar semua permintaan konseling.
     */
    public function index()
    {
        // Asumsi: Siswa sudah login dan model User memiliki relasi 'student'
        $student = Auth::guard('student')->user();

        if (! $student) {
            return ApiResponse::error('Student data not found.', 404);
        }

        $requestCounseling = RequestCounseling::where('student_id', $student->id)
            ->with('schedule.counselor')->get();

        return ApiResponse::success(CounselingResource::collection($requestCounseling));
    }

    /**
     * Membuat permintaan konseling baru.
     */
    public function store(CounselingFormRequest $request)
    {
        // Asumsi: StoreRequest telah memvalidasi title, description, dan urgency
        $student = Auth::guard('student')->user();

        if (! $student) {
            return ApiResponse::error('Student data not found.', 404);
        }

        $counselingRequest = RequestCounseling::create([
            'student_id' => $student->id,
            'title' => $request->title,
            'description' => $request->description,
            'urgency' => $request->urgency,
        ]);

        return ApiResponse::success([
            'request_id' => $counselingRequest->id,
            'available_counselors' => User::where('is_available', true)->get(['id', 'name', 'jabatan']),
        ], 'Counseling request submitted successfully.');
    }

    /**
     * Lihat detail permintaan konseling (request) berdasarkan ID.
     */
    public function show(string $id)
    {
        $student = Auth::guard('student')->user();

        if (! $student) {
            return ApiResponse::error('Student data not found.', 404);
        }

        $requestCounseling = RequestCounseling::where('id', $id)
            ->where('student_id', $student->id)
            ->with('schedule.counselor')
            ->first();

        if (! $requestCounseling) {
            return ApiResponse::error('Counseling request not found.', 404);
        }

        return ApiResponse::success(new CounselingResource($requestCounseling));
    }

    /**
     * Pilih Guru BK dan buat jadwal.
     */
    public function schedule(CounselingScheduleRequest $request, string $request_id)
    {
        $student = Auth::guard('student')->user();

        if (! $student) {
            return ApiResponse::error('Student data not found.', 404);
        }

        $requestCounseling = RequestCounseling::where('id', $request_id)
            ->where('student_id', $student->id)
            ->first();

        if (! $requestCounseling) {
            return ApiResponse::error('Request ID not valid or does not belong to user.', 403);
        }

        // 2. Pastikan Request belum memiliki Schedule yang dikonfirmasi
        if ($requestCounseling->schedule()->exists()) {
            return ApiResponse::error('This request is already scheduled.', 409);
        }

        // 3. Validasi Ketersediaan Konselor dan Slot Waktu (Simulasi)
        $counselor = User::find($request->counselor_id);

        if (! $counselor || ! $counselor->is_available) {
            return ApiResponse::error('Selected counselor is not available.', 400);
        }

        // 4. Buat Schedule baru
        $schedule = ScheduleCounseling::create([
            'request_id' => $requestCounseling->id,
            'counselor_id' => $request->counselor_id,
            'method' => $request->method,
            'schedule_date' => $request->schedule_date,
            'time_slot' => $request->time_slot,
        ]);

        // 5. Update status Request menjadi 'scheduled'
        $requestCounseling->update(['status' => 'scheduled']);

        return ApiResponse::success(new ScheduleCounselingResource($schedule->load('counselor')), 'Counseling schedule successfully proposed. Waiting for counselor confirmation.');
    }
}
