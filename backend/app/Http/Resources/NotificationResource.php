<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => $this->data['type'] ?? null,
            'title' => $this->data['title'] ?? '',
            'message' => $this->data['message'] ?? '',
            'link' => $this->data['action_url'] ?? null,
            'icon' => $this->mapIcon($this->data['type'] ?? null),
            'timestamp' => $this->created_at,
            'isRead' => ! is_null($this->read_at),
        ];
    }

    private function mapIcon(?string $type): string
    {
        return match ($type) {
            'counseling_request_created' => 'tabler:clipboard-check',
            'counseling_scheduled' => 'tabler:calendar-check',
            'counseling_reminder' => 'tabler:bell-ringing',
            'article_published' => 'tabler:book-2',
            'account_password_reset' => 'tabler:lock-check',
            default => 'tabler:bell',
        };
    }
}
