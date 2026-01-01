<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::guard('student')->user();

        return ApiResponse::success(NotificationResource::collection(
            $user->notifications()->latest()->get()
        ));
    }

    public function read(string $id)
    {
        $user = Auth::guard('student')->user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        return ApiResponse::success(code: 201);
    }

    public function readAll()
    {
        $user = Auth::guard('student')->user();

        $user->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return ApiResponse::success(message: 'All notifications marked as read');
    }
}
