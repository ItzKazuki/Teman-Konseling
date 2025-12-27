<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'nis' => $this->nis,
            'nisn' => $this->nisn,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone_number,
            'avatar_url' => $this->avatar_url,
            'classroom_id' => $this->class_room_id,
            'classroom_name' => $this->whenLoaded('classroom', fn () => $this->classroom->name),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
