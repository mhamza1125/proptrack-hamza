<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyCollection;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly PropertyService $propertyService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['city', 'type', 'min_price', 'max_price', 'search']);
        $perPage = (int) $request->get('per_page', 12);
        $perPage = min(max($perPage, 1), 50);

        $listings = $this->propertyService->getPublicListings($filters, $perPage);

        return $this->success(new PropertyCollection($listings));
    }

    public function show(int $id): JsonResponse
    {
        $property = $this->propertyService->findPublic($id);

        if (! $property) {
            return $this->notFound('Listing not found.');
        }

        return $this->success(new PropertyResource($property));
    }
}
