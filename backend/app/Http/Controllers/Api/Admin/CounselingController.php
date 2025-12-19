<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\RequestCounseling;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Konseling', weight: 3)]

class CounselingController extends Controller
{
    public function index()
    {
        $conselings = $requests = RequestCounseling::with('schedule.counselor')->get();

        return ApiResponse::success($requests);
    }
}
