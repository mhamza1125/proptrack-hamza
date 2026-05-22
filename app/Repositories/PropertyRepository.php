<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\PropertyStatus;
use App\Models\Property;
use App\Models\User;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function getPublicListings(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Property::query()
            ->with(['agent:id,name', 'images'])
            ->active()
            ->latest();

        if (! empty($filters['city'])) {
            $query->byCity($filters['city']);
        }

        if (! empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('city', 'like', $term)
                  ->orWhere('address', 'like', $term);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getForAgent(User $agent, int $perPage = 15): LengthAwarePaginator
    {
        return Property::query()
            ->with(['images', 'inquiries'])
            ->withCount('inquiries')
            ->where('user_id', $agent->id)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Property::query()
            ->with(['agent:id,name', 'images'])
            ->withCount('inquiries')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?Property
    {
        return Property::with(['agent:id,name,email', 'images', 'inquiries'])->find($id);
    }

    public function findPublicById(int $id): ?Property
    {
        return Property::with(['agent:id,name', 'images'])
            ->where('status', PropertyStatus::Active)
            ->find($id);
    }

    public function create(array $data): Property
    {
        return Property::create($data);
    }

    public function update(Property $property, array $data): Property
    {
        $property->update($data);
        return $property->fresh();
    }

    public function delete(Property $property): bool
    {
        return (bool) $property->delete();
    }

    public function getDistinctCities(): array
    {
        return Property::query()
            ->active()
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->toArray();
    }
}
