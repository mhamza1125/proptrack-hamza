<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Property $this */
        $imageService = app(ImageService::class);

        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'type'        => $this->type->value,
            'type_label'  => $this->type->label(),
            'status'      => $this->status->value,
            'status_label' => $this->status->label(),
            'price'       => (float) $this->price,
            'price_formatted' => $this->formatted_price,
            'bedrooms'    => $this->bedrooms,
            'bathrooms'   => $this->bathrooms,
            'area'        => $this->area ? (float) $this->area : null,
            'city'        => $this->city,
            'address'     => $this->address,
            'images'      => [
                'featured'   => $imageService->thumbnailUrl($this->featured_image),
                'full'       => $this->featured_image
                    ? asset('storage/' . $this->featured_image)
                    : null,
                'gallery'    => $this->whenLoaded('images', fn () =>
                    $this->images->map(fn ($img) => [
                        'url'        => asset('storage/' . $img->image_path),
                        'is_primary' => $img->is_primary,
                    ])
                ),
            ],
            'agent'       => $this->whenLoaded('agent', fn () => [
                'id'   => $this->agent->id,
                'name' => $this->agent->name,
            ]),
            'inquiries_count' => $this->whenCounted('inquiries'),
            'created_at'  => $this->created_at->toISOString(),
            'updated_at'  => $this->updated_at->toISOString(),
            'links'       => [
                'self'   => url("/api/listings/{$this->id}"),
                'public' => route('listings.show', $this->id),
            ],
        ];
    }
}
