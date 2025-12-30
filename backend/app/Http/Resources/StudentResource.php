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
        $isReallyOnline = $this->is_online && $this->last_seen_at?->gt(now()->subMinutes(5));
        
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
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'village' => $this->village,
            'district' => $this->district,
            'city' => $this->city,
            'province' => $this->province,
            'parent_name' => $this->parent_name,
            'parent_phone_number' => $this->parent_phone_number,
            'is_online' => $isReallyOnline,
            'last_seen_label' => $this->last_seen_at?->diffForHumans(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
