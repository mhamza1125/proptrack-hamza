<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Property;
use App\Models\User;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class PropertyService
{
    public function __construct(
        private readonly PropertyRepositoryInterface $repository,
        private readonly ImageService $imageService,
    ) {}

    public function getPublicListings(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return $this->repository->getPublicListings($filters, $perPage);
    }

    public function getManagedListings(User $user, int $perPage = 15): LengthAwarePaginator
    {
        if ($user->hasRole('admin')) {
            return $this->repository->getAll($perPage);
        }

        return $this->repository->getForAgent($user, $perPage);
    }

    public function findForManagement(int $id): ?Property
    {
        return $this->repository->findById($id);
    }

    public function findPublic(int $id): ?Property
    {
        return $this->repository->findPublicById($id);
    }

    public function create(User $user, array $data, ?UploadedFile $image = null): Property
    {
        $data['user_id'] = $user->id;

        if ($image) {
            $data['featured_image'] = $this->imageService->uploadPropertyImage($image);
        }

        return $this->repository->create($data);
    }

    public function update(Property $property, array $data, ?UploadedFile $image = null): Property
    {
        if ($image) {
            // Remove old image before saving new one
            $this->imageService->deletePropertyImage($property->featured_image);
            $data['featured_image'] = $this->imageService->uploadPropertyImage($image);
        }

        return $this->repository->update($property, $data);
    }

    public function delete(Property $property): bool
    {
        $this->imageService->deletePropertyImage($property->featured_image);
        return $this->repository->delete($property);
    }

    public function getDistinctCities(): array
    {
        return $this->repository->getDistinctCities();
    }
}
