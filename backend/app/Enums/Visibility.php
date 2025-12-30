<?php

namespace App\Enums;

enum Visibility: string
{
    case LOCAL = 'local';
    case PUBLIC = 'public';

    /**
     * Mendapatkan nama disk Laravel yang sesuai.
     * PUBLIC biasanya ke 'public' (storage/app/public)
     * LOCAL biasanya ke 'private' atau 'local' (storage/app)
     */
    public function disk(): string
    {
        return match ($this) {
            self::LOCAL => 'local',
            self::PUBLIC => 'public',
        };
    }

    // Helper untuk menentukan apakah file bisa diakses lewat URL langsung
    public function isExternal(): bool
    {
        return $this === self::PUBLIC;
    }

    public function label(): string
    {
        return match ($this) {
            self::LOCAL => 'Local',
            self::PUBLIC => 'Publik'
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn ($item) => [
            'value' => $item->value,
            'label' => $item->label(),
        ])->toArray();
    }
}
