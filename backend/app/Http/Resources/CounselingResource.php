<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounselingResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'urgency' => $this->urgency,
            'status' => $this->status,
            'student' => $this->whenLoaded('student', fn () => new StudentResource($this->student)),
            'schedule' => $this->whenLoaded('schedule', fn () => new ScheduleCounselingResource($this->schedule)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
