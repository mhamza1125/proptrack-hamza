<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(
        private readonly PropertyService $propertyService,
    ) {}

    public function index(): View
    {
        $properties = $this->propertyService->getManagedListings(auth()->user());

        return view('properties.index', compact('properties'));
    }

    public function create(): View
    {
        Gate::authorize('create', Property::class);

        $types    = PropertyType::cases();
        $statuses = PropertyStatus::cases();

        return view('properties.create', compact('types', 'statuses'));
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $property = $this->propertyService->create(
            user:  $request->user(),
            data:  $request->except('featured_image'),
            image: $request->file('featured_image'),
        );

        return redirect()
            ->route('properties.index')
            ->with('success', "Property \"{$property->title}\" created successfully.");
    }

    public function show(Property $property): View
    {
        Gate::authorize('view', $property);

        $property->load(['agent:id,name', 'images', 'inquiries']);

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property): View
    {
        Gate::authorize('update', $property);

        $types    = PropertyType::cases();
        $statuses = PropertyStatus::cases();

        return view('properties.edit', compact('property', 'types', 'statuses'));
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $this->propertyService->update(
            property: $property,
            data:     $request->except('featured_image'),
            image:    $request->file('featured_image'),
        );

        return redirect()
            ->route('properties.index')
            ->with('success', "Property \"{$property->title}\" updated successfully.");
    }

    public function destroy(Property $property): RedirectResponse
    {
        Gate::authorize('delete', $property);

        if ($property->hasActiveInquiries()) {
            return back()->with('error', 'Cannot delete a property with active inquiries (New or In Review).');
        }

        $title = $property->title;
        $this->propertyService->delete($property);

        return redirect()
            ->route('properties.index')
            ->with('success', "Property \"{$title}\" deleted successfully.");
    }
}
