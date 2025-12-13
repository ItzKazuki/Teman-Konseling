<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Admin\ArticleCategoryController;
use App\Http\Controllers\Api\Admin\ArticleController;
use App\Http\Controllers\Api\Admin\ClassroomController as AdminClassroomController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\AccountController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Common\FileController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\Student\ArticleController as StudentArticleController;
use App\Http\Middleware\CheckRoleIsBk;
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
            'version' => config('app.version'),
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
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('files', FileController::class);
        Route::get('/files/{id}/blob', [FileController::class, 'blob']);
    });

    // untuk bk
    Route::group(['middleware' => ['auth:user', CheckRoleIsBk::class], 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('students', StudentController::class);
        Route::apiResource('articles', ArticleController::class);

        // master data admin
        Route::group(['prefix' => 'master-data', 'as' => 'master-data.'], function () {
            Route::apiResource('classrooms', AdminClassroomController::class);
            Route::apiResource('article-categories', ArticleCategoryController::class);
        });
    });

    Route::group(['prefix' => 'master-data', 'as' => 'master-data.'], function () {
        Route::get('classrooms', [MasterDataController::class, 'classrooms']);
        Route::get('article-categories', [MasterDataController::class, 'articleCategory']);
        Route::get('teachers', [MasterDataController::class, 'teachers']);
    });
});
