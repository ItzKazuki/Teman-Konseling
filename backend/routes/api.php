<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Admin\ClassroomController as AdminClassroomController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Middleware\CheckRoleIsBk;

Route::prefix('v1')->group(function () {
    /**
     * App Details
     */
    Route::get('/', function () {
        return ApiResponse::success([
            'app_name' => config('app.name'),
            'version' => config('app.version'),
            'maintainer' => 'ItzKazuki (Cha)'
        ]);
    });

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('login', [LoginController::class, 'index']);
        Route::post('logout', [LogoutController::class, 'index'])->middleware('auth:sanctum');

        // Route::post('password/forgot', [ResetPasswordController::class, 'reset']);
        // Route::post('password/validate-otp', [ResetPasswordController::class, 'validateOtp']);
        // Route::post('password/reset', [ResetPasswordController::class, 'change']);
    });

     Route::middleware(['auth:sanctum'])->group(function () {
        // Route::apiResource('files', FileController::class);
        // Route::get('/files/{id}/blob', [FileController::class, 'blob']);

        Route::get('classrooms', [ClassroomController::class, 'index']);
    });


    Route::group(['middleware' => ['auth:sanctum', CheckRoleIsBk::class], 'prefix' => 'admin', 'as' => 'admin.'], function () {

        Route::apiResource('users', UserController::class);
        Route::apiResource('classrooms', AdminClassroomController::class);
        Route::apiResource('students', StudentController::class);
    });

});
