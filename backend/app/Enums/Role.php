<?php

namespace App\Enums;

enum Role: string
{
    case BK = 'bk';
    case GURU = 'guru';
    case STAFF = 'staff';

    // Mendapatkan label untuk tampilan UI
    public function label(): string
    {
        return match ($this) {
            self::BK => 'Guru BK',
            self::GURU => 'Guru Mata Pelajaran',
            self::STAFF => 'Staf Administrasi',
        };
    }

    // Mempermudah pengecekan akses di Policy atau Controller
    public function isManagement(): bool
    {
        return in_array($this, [self::BK, self::STAFF]);
    }

    public function canManageArticles(): bool
    {
        return $this === self::BK;
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn ($item) => [
            'value' => $item->value,
            'label' => $item->label(),
        ])->toArray();
    }
}
