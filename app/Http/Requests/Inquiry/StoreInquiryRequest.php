<?php

declare(strict_types=1);

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // guests may submit inquiries
    }

    public function rules(): array
    {
        return [
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'name'        => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:150'],
            'phone'                    => ['nullable', 'string', 'max:30'],
            'preferred_contact_method' => ['nullable', 'in:Phone,Email,WhatsApp'],
            'message'                  => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }
}
