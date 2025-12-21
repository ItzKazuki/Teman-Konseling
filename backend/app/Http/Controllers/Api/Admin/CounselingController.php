<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CounselingResource;
use App\Models\RequestCounseling;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Konseling', weight: 3)]

class CounselingController extends Controller
{
    public function index()
    {
        $counselings = RequestCounseling::with(['schedule.counselor', 'student.classroom'])->get();

        return ApiResponse::success(CounselingResource::collection($counselings));
    }

    public function show(string $id)
    {
        $requestCounseling = RequestCounseling::where('id', $id)
            ->with(['schedule.counselor', 'student.classroom'])
            ->first();

        if (! $requestCounseling) {
            return response()->json(['message' => 'Counseling request not found.'], 404);
        }

        return ApiResponse::success(new CounselingResource($requestCounseling));
    }
}
