<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin: Data Pengguna', weight: 3)]
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
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        User::create($validatedData);

        return ApiResponse::success(null, 'Berhasil menambahkan pengguna baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('Pengguna tidak ditemukan', 404);
        }

        return ApiResponse::success(new UserResource($user), 'data pengguna ditemukan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('Pengguna tidak ditemukan', 404);
        }

        $validatedData = $request->validated();

        $user->update($validatedData);

        return ApiResponse::success(null, 'Berhasil mengubah detail pengguna');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('Pengguna tidak ditemukan', 404);
        }

        $user->delete();

        return ApiResponse::success(null, 'Berhasil menghapus pengguna');
    }
}
