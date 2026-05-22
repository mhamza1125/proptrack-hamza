<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'preferred_contact_method',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => InquiryStatus::class,
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', InquiryStatus::New);
    }

    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
