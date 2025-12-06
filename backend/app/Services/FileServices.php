<?php

namespace App\Services;

use App\Enums\Visibility;
use App\Helpers\DebugLog;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileService
{
    /**
     * This method, handle file upload from request and store file metadata in database.
     */
    public static function upload(
        UploadedFile $file,
        User $user,
        string $defaultPath,
        Visibility $visibility = Visibility::LOCAL
    ): ?File {
        $allowedMimes = config('filesystems.allowed_types');

        try {
            if (! in_array($file->getMimeType(), $allowedMimes)) {
                throw new \InvalidArgumentException('Tipe file tidak diperbolehkan.');
            }

            // Transaksi untuk menjaga konsistensi data
            return DB::transaction(function () use ($file, $user, $defaultPath, $visibility) {
                // Simpan file ke storage
                DebugLog::info('DISK', $visibility->value);
                $path = $file->store($defaultPath, $visibility->value);

                // Simpan ke database
                return File::create([
                    'uploader_id' => $user->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'visibility' => $visibility,
                ]);
            });
        } catch (Throwable $e) {
            // Log error supaya bisa dilacak
            DebugLog::error('Gagal upload file', [
                'user_id' => $user->id,
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return null; // null = gagal upload
        }
    }

    /**
     * This method, attach existing file from storage to database record.
     */
    public static function attachFromStorage(
        string $path,
        User $user,
        Visibility $visibility = Visibility::LOCAL
    ): bool {
        $file = Storage::disk($visibility);

        return File::create([
            'uploader_id' => $user->id,
            'file_name' => basename($path),
            'file_path' => $path,
            'mime_type' => mime_content_type($file->path($path)),
            'size' => $file->size($path),
            'visibility' => $visibility,
        ]);
    }
}
