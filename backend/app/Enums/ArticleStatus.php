<?php

namespace App\Enums;

enum ArticleStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draf',
            self::PUBLISHED => 'Diterbitkan',
            self::ARCHIVED => 'Diarsipkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-gray-100 text-gray-700 border-gray-200',
            self::PUBLISHED => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            self::ARCHIVED => 'bg-amber-100 text-amber-700 border-amber-200',
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
