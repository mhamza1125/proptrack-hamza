<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function __construct(
        private readonly PropertyService $propertyService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only([
            'city', 'type', 'min_price', 'max_price', 'search',
        ]);

        $properties = $this->propertyService->getPublicListings($filters);
        $cities     = $this->propertyService->getDistinctCities();
        $types      = PropertyType::cases();

        return view('public.index', compact('properties', 'filters', 'cities', 'types'));
    }

    public function show(int $id): View|\Illuminate\Http\RedirectResponse
    {
        $property = $this->propertyService->findPublic($id);

        if (! $property) {
            return redirect()->route('home')->with('error', 'Property not found or no longer available.');
        }

        return view('public.show', compact('property'));
    }
}
