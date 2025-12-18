<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

#[Group('Admin: Data Siswa', weight: 3)]
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('classroom')->get();

        return ApiResponse::success(StudentResource::collection($students), 'Berhasil mengambil data siswa');
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
            'phone' => $student->phone,
            'address' => $student->address,
            'parent_name' => $student->parent_name,
            'parent_phone' => $student->parent_phone,
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
    public function update(Request $request, string $id)
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
}
