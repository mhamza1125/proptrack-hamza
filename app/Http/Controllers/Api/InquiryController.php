<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inquiry\StoreInquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Services\InquiryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly InquiryService $inquiryService,
    ) {}

    public function store(StoreInquiryRequest $request): JsonResponse
    {
        $inquiry = $this->inquiryService->submit($request->validated());

        return $this->created(
            new InquiryResource($inquiry),
            'Inquiry submitted successfully.',
        );
    }
}
