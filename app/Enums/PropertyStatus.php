<?php

declare(strict_types=1);

namespace App\Enums;

enum PropertyStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Sold     = 'sold';
    case Rented   = 'rented';

    public function label(): string
    {
        return match($this) {
            self::Active   => 'Active',
            self::Inactive => 'Inactive',
            self::Sold     => 'Sold',
            self::Rented   => 'Rented',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active   => 'green',
            self::Inactive => 'gray',
            self::Sold     => 'red',
            self::Rented   => 'blue',
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
