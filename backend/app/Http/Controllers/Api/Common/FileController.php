<?php

namespace App\Http\Controllers\Api\Common;

use App\Enums\Visibility;
use App\Helpers\ApiResponse;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Dedoc\Scramble\Attributes\Group;

#[Group('File: Manajemen File upload')]
class FileController
{
    /**
     * Daftar File yang telah di unggah.
     */
    public function index(Request $request): JsonResponse
    {
        return ApiResponse::success(FileResource::collection($request->user()->files), 'Daftar file yang di unggah');
    }

    /**
     * Upload file ke server.
     */
    public function store(Request $request): JsonResponse
    {
        if (! $request->hasFile('file')) {
            return ApiResponse::error('No file received', 400);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:20480', // max 20MB misalnya
            'file_location' => 'nullable|string', // this vulnerability can be mitigated by sanitizing the input in the service
            'visibility' => [
                'required',
                new Enum(Visibility::class),
            ],
        ]);

        $defaultPath = $validated['file_location'] ?? $request->user()->id;

        $visibility = $validated['visibility'] === 'public' ? Visibility::PUBLIC : Visibility::LOCAL; // change public or private to public or local

        $file = FileService::upload(
            $request->file('file'),
            $request->user(),
            $defaultPath,
            $visibility
        );

        if (! $file) {
            return ApiResponse::error('File upload failed', 500);
        }

        return ApiResponse::success(new FileResource($file), 'Berhasil unggah file', 201);
    }

    /**
     * Mengambil data detail file.
     */
    public function show(string $id): JsonResponse
    {
        $file = File::findOrFail($id);

        return ApiResponse::success(new FileResource($file), 'Detail file');
    }

    /**
     * Stream Blob file
     */
    public function blob(string $id): StreamedResponse|JsonResponse
    {
        $file = File::findOrFail($id);

        $path = $file->file_path;
        $disk = $file->visibility;

        if (! Storage::disk($disk)->exists($path)) {
            return ApiResponse::error('File not found', 404);
        }

        // streaming file
        return new StreamedResponse(function () use ($disk, $path) {
            $stream = Storage::disk($disk)->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="'.basename($file->file_name).'"',
            'Accept-Ranges' => 'bytes', // penting untuk video/audio
        ]);
    }

    /**
     * Hapus file yang ada di server.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $file = File::findOrFail($id);

        if ($request->user()->id !== $file->uploader_id) {
            return ApiResponse::error('Unauthorized', 403);
        }

        $file->delete();

        return ApiResponse::success(null, 'File berhasil dihapus');
    }
}
