<?php

namespace App\Http\Controllers\Api;

use App\Enums\ArticleStatus;
use App\Enums\Role;
use App\Enums\Visibility;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCategoryResource;
use App\Http\Resources\ClassRoomResource;
use App\Http\Resources\CounselorResource;
use App\Http\Resources\TeacherResource;
use App\Models\ArticleCategory;
use App\Models\ClassRoom;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;

#[Group('Reference', weight: 11)]
class MasterDataController extends Controller
{
    /**
     * Show All Teachers
     */
    public function teachers()
    {
        $teachers = User::whereIn('role', [Role::GURU, Role::BK])->get();

        return ApiResponse::success(TeacherResource::collection($teachers));
    }

    /**
     * Show All Counselor
     */
    public function counselors()
    {
        $counselors = User::where('role', Role::BK)->get();

        return ApiResponse::success(CounselorResource::collection($counselors));
    }

    /**
     * Show All Classrooms
     */
    public function classrooms()
    {
        $classrooms = ClassRoom::with('homeroomTeacher')->get();

        $classrooms->makeHidden(['homeroom_teacher']);

        return ApiResponse::success(ClassRoomResource::collection($classrooms), 'Berhasil mengambil data kelas');
    }

    /**
     * Show all Article Category
     */
    public function articleCategory()
    {
        $categories = ArticleCategory::all();

        return ApiResponse::success(ArticleCategoryResource::collection($categories));
    }

    /**
     * Menampilkan visibility file
     */
    public function fileVisibility()
    {
        return ApiResponse::success(Visibility::options());
    }

    /**
     * Menampilkan role pengguna
     */
    public function roles()
    {
        return ApiResponse::success(Role::options());
    }

    /**
     * Menampilkan status artikel
     */
    public function articleStatus()
    {
        return ApiResponse::success(ArticleStatus::options());
    }
}
