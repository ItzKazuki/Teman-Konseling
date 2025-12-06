<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uploadedBy' => $this->whenLoaded('uploader', fn () => $this->uploader->name),
            'fileName' => $this->file_name,
            'filePath' => $this->file_path,
            'mimeType' => $this->mime_type,
            'size' => $this->size,
            'visibility' => $this->visibility,
            'uploadedAt' => $this->created_at,
        ];

    }
}
