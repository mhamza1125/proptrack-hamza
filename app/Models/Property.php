<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'status',
        'price',
        'bedrooms',
        'bathrooms',
        'area',
        'city',
        'address',
        'featured_image',
    ];

    protected function casts(): array
    {
        return [
            'type'      => PropertyType::class,
            'status'    => PropertyStatus::class,
            'price'     => 'decimal:2',
            'area'      => 'decimal:2',
            'bedrooms'  => 'integer',
            'bathrooms' => 'integer',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PropertyStatus::Active);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopePriceBetween(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return asset('images/property-placeholder.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format((float) $this->price, 0, '.', ',');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function hasActiveInquiries(): bool
    {
        return $this->inquiries()
            ->whereIn('status', [
                \App\Enums\InquiryStatus::New->value,
                \App\Enums\InquiryStatus::InReview->value,
            ])
            ->exists();
    }
}
