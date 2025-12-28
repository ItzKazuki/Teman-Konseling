<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\UserResetPasswordMail;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

#[Group('Admin: Data Pengguna', weight: 3)]
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $query->orderByRaw("FIELD(role, 'bk', 'guru', 'staff')");

        $users = $query->paginate($perPage);

        $users->getCollection()->transform(function ($user) {
            return new UserResource($user);
        });

        return ApiResponse::success($users, 'Berhasil mengambil data pengguna');
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

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(8);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        Mail::to($user->email)->send(new UserResetPasswordMail($user, $newPassword));

        return ApiResponse::success(null, 'Kata sandi berhasil direset dan dikirim ke email pengguna.');
    }
}
