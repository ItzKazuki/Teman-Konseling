<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Admin\ArticleController;
use App\Http\Controllers\Api\Admin\CounselingController;
use App\Http\Controllers\Api\Admin\MoodController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\AccountController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Common\FileController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MasterData\ArticleCategoryController;
use App\Http\Controllers\Api\MasterData\ClassroomController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\ProfileController as UserProfileController;
use App\Http\Controllers\Api\Staff\ArticleController as StaffArticleController;
use App\Http\Controllers\Api\Staff\StudentController as StaffStudentController;
use App\Http\Controllers\Api\Student\ArticleController as StudentArticleController;
use App\Http\Controllers\Api\Student\CounselingController as StudentCounselingController;
use App\Http\Controllers\Api\Student\MoodController as StudentMoodController;
use App\Http\Controllers\Api\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Api\Teacher\ArticleController as TeacherArticleController;
use App\Http\Controllers\Api\Teacher\MoodController as TeacherMoodController;
use App\Http\Controllers\Api\Teacher\StudentController as TeacherStudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    /**
     * App Details
     *
     * @unauthenticated
     */
    Route::get('/', function () {
        return ApiResponse::success([
            'app_name' => config('app.name'),
            'version' => config('app.env') === 'production' ? config('app.version') : config('app.version').'-dev',
            'maintainer' => 'ItzKazuki (Cha)',
        ]);
    });

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('user/login', [LoginController::class, 'index']);
        Route::post('student/login', [LoginController::class, 'student']);
        Route::post('logout', [LogoutController::class, 'index'])->middleware('auth:sanctum');
        Route::get('student/me', [AccountController::class, 'student'])->middleware('auth:student');
        Route::get('user/me', [AccountController::class, 'user'])->middleware('auth:user');

        Route::post('password/forgot', [ResetPasswordController::class, 'reset']);
        Route::post('password/validate-otp', [ResetPasswordController::class, 'validateOtp']);
        Route::post('password/reset', [ResetPasswordController::class, 'change']);
    });

    Route::group(['middleware' => ['auth:student'], 'prefix' => 'student', 'as' => 'student.'], function () {
        Route::get('articles', [StudentArticleController::class, 'index']);
        Route::get('articles/{slug}', [StudentArticleController::class, 'show']);

        Route::get('counseling', [StudentCounselingController::class, 'index']);
        Route::get('counseling/available-slots', [StudentCounselingController::class, 'getAvailableSlots']);
        Route::get('counseling/{id}', [StudentCounselingController::class, 'show']);
        Route::post('counseling/new-request', [StudentCounselingController::class, 'store']);
        Route::post('counseling/schedule/{request_id}', [StudentCounselingController::class, 'schedule']);

        Route::post('daily-moods', [StudentMoodController::class, 'store']);
        Route::get('daily-moods/check', [StudentMoodController::class, 'checkStatus']);

        Route::put('profile/update', [StudentProfileController::class, 'update']);
        Route::put('profile/password/update', [StudentProfileController::class, 'changePassword']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('files', FileController::class);
        Route::get('/files/{id}/blob', [FileController::class, 'blob']);

        Route::put('profile/update', [UserProfileController::class, 'update']);
        Route::put('profile/password/update', [UserProfileController::class, 'changePassword']);
        Route::patch('profile/status', [UserProfileController::class, 'status']);

        Route::get('dashboard-overview', [DashboardController::class, 'overview']);
    });

    // untuk bk as a admin
    Route::group(['middleware' => ['auth:user', 'role:bk'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword']);
        Route::post('students/{student}/reset-password', [StudentController::class, 'resetPassword']);
        Route::apiResource('users', UserController::class);
        Route::get('students/details/{id}', [StudentController::class, 'detail']);
        Route::get('counselings/summary', [CounselingController::class, 'summary']);
        Route::apiResource('students', StudentController::class);
        Route::apiResource('articles', ArticleController::class);
        Route::apiResource('counselings', CounselingController::class);

        Route::get('moods', [MoodController::class, 'index']);
        Route::get('moods/history', [MoodController::class, 'history']);
    });

    Route::group(['middleware' => ['auth:user', 'role:bk,staff'], 'prefix' => 'master-data', 'as' => 'master-data.'], function () {
        Route::apiResource('classrooms', ClassroomController::class);
        Route::apiResource('article-categories', ArticleCategoryController::class);
    });

    Route::group(['middleware' => ['auth:user', 'role:staff'], 'prefix' => 'staff', 'as' => 'staff.'], function () {
        Route::get('students/details/{id}', [StaffStudentController::class, 'detail']);
        Route::apiResource('articles', StaffArticleController::class);
        Route::apiResource('students', StaffStudentController::class);
    });

    Route::group(['middleware' => ['auth:user', 'role:guru'], 'prefix' => 'teacher', 'as' => 'teacher.'], function () {
        Route::apiResource('articles', TeacherArticleController::class)->except(['destroy']);
        Route::get('students', [TeacherStudentController::class, 'index']);
        Route::get('students/details/{id}', [TeacherStudentController::class, 'detail']);
        Route::get('moods', [TeacherMoodController::class, 'index']);
    });

    Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'reference', 'as' => 'reference.'], function () {
        Route::get('classrooms', [MasterDataController::class, 'classrooms']);
        Route::get('article-categories', [MasterDataController::class, 'articleCategory']);
        Route::get('teachers', [MasterDataController::class, 'teachers']);
        Route::get('counselors', [MasterDataController::class, 'counselors']);

        Route::get('roles', [MasterDataController::class, 'roles']);
        Route::get('visibility-file', [MasterDataController::class, 'fileVisibility']);
        Route::get('article-status', [MasterDataController::class, 'articleStatus']);

    });
});
