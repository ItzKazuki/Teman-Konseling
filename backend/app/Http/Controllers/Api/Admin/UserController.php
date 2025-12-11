<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Data Pengguna')]
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return ApiResponse::success(UserResource::collection($users), 'berhasil mengambil data staff sekolah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if(!$user) {
            return ApiResponse::error('Pengguna tidak ditemukan', 404);
        }

        return ApiResponse::success(new UserResource($user), 'data pengguna ditemukan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if(!$user) {
            return ApiResponse::error('Pengguna tidak ditemukan', 404);
        }

        $user->delete();

        return ApiResponse::success(null, 'Berhasil menghapus pengguna');
    }
}
