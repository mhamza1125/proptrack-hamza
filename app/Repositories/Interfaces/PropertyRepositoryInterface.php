<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Property;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PropertyRepositoryInterface
{
    public function getPublicListings(array $filters, int $perPage = 12): LengthAwarePaginator;

    public function getForAgent(User $agent, int $perPage = 15): LengthAwarePaginator;

    public function getAll(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Property;

    public function findPublicById(int $id): ?Property;

    public function create(array $data): Property;

    public function update(Property $property, array $data): Property;

    public function delete(Property $property): bool;

    public function getDistinctCities(): array;
}
