<?php

declare(strict_types=1);

namespace App\Enums;

enum InquiryStatus: string
{
    case New       = 'new';
    case InReview  = 'in_review';
    case Contacted = 'contacted';
    case Closed    = 'closed';

    public function label(): string
    {
        return match($this) {
            self::New       => 'New',
            self::InReview  => 'In Review',
            self::Contacted => 'Contacted',
            self::Closed    => 'Closed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::New       => 'blue',
            self::InReview  => 'yellow',
            self::Contacted => 'purple',
            self::Closed    => 'gray',
        };
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
