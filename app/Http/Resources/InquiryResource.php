<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Inquiry $this */
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'message'    => $this->message,
            'status'     => $this->status->value,
            'status_label' => $this->status->label(),
            'property'   => $this->whenLoaded('property', fn () => [
                'id'    => $this->property->id,
                'title' => $this->property->title,
            ]),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
