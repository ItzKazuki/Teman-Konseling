<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'student_id'   => $this->student_id,
            'student_name' => $this->whenLoaded('student', fn () => $this->student->name),
            'student_avatar_url' => $this->whenLoaded('student', fn () => $this->student->avatar_url),
            'student_classroom' => $this->whenLoaded('student', fn () => $this->student->classroom->name),
            'emotion_name' => $this->emotion_name,
            'magnitude'    => $this->magnitude,
            'story'        => $this->story,
            'is_custom'    => (bool) $this->is_custom,
            'created_at'   => $this->created_at,
            'time_ago' => $this->created_at->diffForHumans()
        ];
    }
}
