<?php

declare(strict_types=1);

namespace App\Http\Requests\Property;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Property::class);
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'type'        => ['required', new Enum(PropertyType::class)],
            'status'      => ['required', new Enum(PropertyStatus::class)],
            'price'       => ['required', 'numeric', 'min:1', 'max:99999999'],
            'bedrooms'    => ['nullable', 'integer', 'min:0', 'max:50'],
            'bathrooms'   => ['nullable', 'integer', 'min:0', 'max:50'],
            'area'        => ['nullable', 'numeric', 'min:1', 'max:999999'],
            'city'        => ['required', 'string', 'max:100'],
            'address'     => ['required', 'string', 'max:255'],
            'featured_image' => [
                'nullable', 'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'featured_image.max' => 'Image must be no larger than 5MB.',
        ];
    }
}
