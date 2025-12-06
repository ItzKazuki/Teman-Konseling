<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    // another route and groups definition in here
});
