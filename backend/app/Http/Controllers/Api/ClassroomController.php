<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = ClassRoom::all();

        return ApiResponse::success(ClassRoomResource::collection($classrooms), 'Berhasil mengambil data kelas');
    }
}
