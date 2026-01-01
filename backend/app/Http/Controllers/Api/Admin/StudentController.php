<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Mail\StudentResetPasswordMail;
use App\Models\Student;
use App\Notifications\AccountPasswordResetNotification;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

#[Group('Admin: Data Siswa', weight: 3)]
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = Student::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Filter berdasarkan Kelas
        if ($request->has('classId') && $request->classId != '') {
            $query->where('class_room_id', $request->classId);
        }

        $students = $query->with('classroom')->paginate($perPage);

        $students->getCollection()->transform(function ($student) {
            return new StudentResource($student);
        });

        return ApiResponse::success($students, 'Berhasil mengambil data siswa');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $validated = $request->validated();

        // Hashing password hanya jika ada
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $student = Student::create($validated);

        return ApiResponse::success(null, 'Siswa berhasil ditambahkan', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);

        if (! $student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        return ApiResponse::success(new StudentResource($student), 'berhasil mengambil data siswa');
    }

    /**
     * Display detail student.
     */
    public function detail(string $id)
    {
        $student = Student::with(['dailyMoods' => function ($query) {
            $query->latest()->take(30);
        }])->find($id);

        if (! $student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        return ApiResponse::success([
            'name' => $student->name,
            'nisn' => $student->nisn,
            'avatar_url' => $student->avatar_url,
            'phone' => $student->phone_number,
            'address' => $student->address,
            'parent_name' => $student->parent_name,
            'parent_phone' => $student->parent_phone_number,
            'classroom' => $student->classroom->name,
            'mood_history' => $student->dailyMoods->map(fn ($m) => [
                'id' => $m->id,
                'emotion_name' => $m->emotion_name,
                'magnitude' => $m->magnitude,
                'story' => $m->story,
                'formatted_date' => $m->created_at->translatedFormat('d M Y'),
            ]),
            'chart_labels' => $student->dailyMoods->reverse()->pluck('created_at')->map(fn ($d) => $d->format('d M')),
            'chart_data' => $student->dailyMoods->reverse()->pluck('magnitude'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, string $id)
    {
        $student = Student::find($id);

        if (! $student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        $validated = $request->validated();

        // Hashing password hanya jika password disertakan dalam request
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Hapus password dari array agar tidak menimpa password lama dengan null
            unset($validated['password']);
        }

        $student->update($validated);

        return ApiResponse::success(null, 'Data siswa berhasil diperbaharui', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if (! $student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        $student->delete();

        return ApiResponse::success(null, 'berhasil menghapus data siswa');
    }

    public function resetPassword(Student $student)
    {
        $newPassword = Str::random(8);

        $student->update([
            'password' => Hash::make($newPassword),
        ]);

        Mail::to($student->email)->send(new StudentResetPasswordMail($student, $newPassword));

        $student->notify(new AccountPasswordResetNotification());

        return ApiResponse::success(null, 'Kata sandi berhasil direset dan dikirim ke email pengguna.');
    }
}
