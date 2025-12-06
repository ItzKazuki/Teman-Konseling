<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ClassRoom;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomResource;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classroom = ClassRoom::find($id);

        if(!$classroom) {
            return ApiResponse::error('Kelas tidak ditemukan', 404);
        }

        return ApiResponse::success(new ClassRoomResource($classroom), 'berhasil mengambil data kelas');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $classroom = ClassRoom::find($id);

        if(!$classroom) {
            return ApiResponse::error('Kelas tidak ditemukan', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $classroom = ClassRoom::find($id);

        if(!$classroom) {
            return ApiResponse::error('Kelas tidak ditemukan', 404);
        }

        $classroom->delete();

        return ApiResponse::success(null, 'Berhasil menghapus data kelas');
    }
}
