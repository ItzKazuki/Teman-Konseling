<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin Master Data: Kelas', weight: 10)]
class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = ClassRoom::all();

        return ApiResponse::success(ClassRoomResource::collection($classrooms), 'Berhasil mengambil data kelas');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassroomRequest $request)
    {
        $validatedData = $request->validated();

        $classroom = Classroom::create($validatedData);

        return ApiResponse::success(null, 'Berhasil membuat kelas baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classroom = ClassRoom::find($id);

        if (! $classroom) {
            return ApiResponse::error('Kelas tidak ditemukan', 404);
        }

        return ApiResponse::success(new ClassRoomResource($classroom), 'berhasil mengambil data kelas');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomRequest $request, string $id)
    {
        $classroom = ClassRoom::find($id);

        if (! $classroom) {
            return ApiResponse::error('Kelas tidak ditemukan', 404);
        }

        $validatedData = $request->validated();

        $classroom->update($validatedData);

        return ApiResponse::success(null, 'Berhasil mengubah detail kelas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $classroom = ClassRoom::find($id);

        if (! $classroom) {
            return ApiResponse::error('Kelas tidak ditemukan', 404);
        }

        $classroom->delete();

        return ApiResponse::success(null, 'Berhasil menghapus data kelas');
    }
}
