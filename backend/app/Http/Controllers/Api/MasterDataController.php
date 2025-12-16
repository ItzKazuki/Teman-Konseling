<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Models\User;
use App\Models\ClassRoom;
use App\Helpers\ApiResponse;
use App\Models\ArticleCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\ClassRoomResource;
use App\Http\Resources\ArticleCategoryResource;
use Dedoc\Scramble\Attributes\Group;

#[Group('Master Data', weight: 11)]
class MasterDataController extends Controller
{
    /**
     * Show All Teachers
     *
     * @unauthenticated
     */
    public function teachers()
    {
        $teachers = User::where('role', Role::GURU)->get();

        return ApiResponse::success(TeacherResource::collection($teachers));
    }

    /**
     * Show All Classrooms
     *
     * @unauthenticated
     */
    public function classrooms()
    {
        $classrooms = ClassRoom::with('homeroomTeacher')->get();

        $classrooms->makeHidden(['homeroom_teacher']);

        return ApiResponse::success(ClassRoomResource::collection($classrooms), 'Berhasil mengambil data kelas');
    }

    /**
     * Show all article category
     *
     * @unauthenticated
     */
    public function articleCategory()
    {
        $categories = ArticleCategory::all();

        return ApiResponse::success(ArticleCategoryResource::collection($categories));
    }
}
