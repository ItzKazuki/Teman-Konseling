<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('student')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'village' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            // 1. Hapus file lama jika ada dan bukan file default
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // 2. Simpan file baru (hasilnya: "avatars/students/namafile.jpg")
            $path = $request->file('avatar')->store('avatars/students', 'public');

            // 3. Masukkan ke array validated untuk diupdate ke kolom avatar_path
            $validated['avatar_path'] = $path;
        }

        $user->update($validated);

        // Load Accessor avatar_url agar Nuxt menerima URL yang sudah jadi
        $user->append('avatar_url');

        return ApiResponse::success(new StudentResource($user), 'Profil berhasil diperbarui');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::guard('student')->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success(null, 'Kata sandi berhasil diubah');
    }
}
