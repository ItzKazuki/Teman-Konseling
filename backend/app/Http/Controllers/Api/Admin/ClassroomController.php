<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin Master Data: Kelas', weight: 10)]
class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = ClassRoom::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Filter berdasarkan Kelas
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }
        
        $classrooms = $query->with('homeroomTeacher')->paginate($perPage);

        $classrooms->getCollection()->transform(function ($classroom) {
            return new ClassRoomResource($classroom);
        });

        return ApiResponse::success($classrooms, 'Berhasil mengambil data kelas');
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
