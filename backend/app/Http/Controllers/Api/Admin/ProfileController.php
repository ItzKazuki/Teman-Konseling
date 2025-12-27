<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nip' => ['required', 'string', 'unique:users,nip,'.$user->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'jabatan' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'is_available' => ['required', 'boolean'],
        ]);

        $user->update($validated);

        return ApiResponse::success(new UserResource($user), 'Profil berhasil diperbarui!');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return ApiResponse::success(null, 'Kata sandi berhasil diubah!');
    }
}
