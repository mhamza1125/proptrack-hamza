<?php

declare(strict_types=1);

namespace App\Enums;

enum PropertyType: string
{
    case House      = 'house';
    case Apartment  = 'apartment';
    case Commercial = 'commercial';
    case Land       = 'land';

    public function label(): string
    {
        return match($this) {
            self::House      => 'House',
            self::Apartment  => 'Apartment',
            self::Commercial => 'Commercial',
            self::Land       => 'Land',
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
