<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Student;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Data Siswa', weight: 3)]
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();

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

        if(!$student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        return ApiResponse::success(new StudentResource($student), 'berhasil mengambil data siswa');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        if(!$student) {
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

        if(!$student) {
            return ApiResponse::error('Data siswa tidak ditemukan', 404);
        }

        $student->delete();

        return ApiResponse::success(null, 'berhasil menghapus data siswa');
    }
}
