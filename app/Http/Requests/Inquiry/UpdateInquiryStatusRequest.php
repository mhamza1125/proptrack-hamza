<?php

declare(strict_types=1);

namespace App\Http\Requests\Inquiry;

use App\Enums\InquiryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateInquiryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(InquiryStatus::class)],
        ];
    }
}
