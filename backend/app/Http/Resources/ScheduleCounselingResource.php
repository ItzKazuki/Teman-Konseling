<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleCounselingResource extends JsonResource
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
            'request_id' => $this->request_id,
            'counselor_id' => $this->counselor_id,
            'method' => $this->method,
            'schedule_date' => optional($this->schedule_date)->format('Y-m-d'),
            'time_slot' => $this->time_slot, // sudah bersih "H:i"
            'status' => $this->status,
            'confirmed_at' => $this->confirmed_at,
            'counselor' => $this->whenLoaded(
                'counselor',
                fn () => new CounselorResource($this->counselor)
            ),
        ];
    }
}
