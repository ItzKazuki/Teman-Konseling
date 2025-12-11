<?php

namespace App\Http\Controllers\Api\Common;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Master Data')]
class ClassroomController extends Controller
{
    /**
     * Data Kelas
     */
    public function index()
    {
        $classrooms = ClassRoom::all();

        return ApiResponse::success(ClassRoomResource::collection($classrooms), 'Berhasil mengambil data kelas');
    }
}
