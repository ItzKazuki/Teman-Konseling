<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#[Group('Account', weight: 1)]
class AccountController extends Controller
{
    /**
     * Detail data siswa.
     */
    public function student(Request $request)
    {
        $student = Auth::guard('student')->user()->load('classroom');

        return ApiResponse::success(new StudentResource($student));
    }

    /**
     * Detail pengguna.
     */
    public function user(Request $request)
    {
        $user = Auth::guard('user')->user();

        return ApiResponse::success(new UserResource($user));
    }
}
