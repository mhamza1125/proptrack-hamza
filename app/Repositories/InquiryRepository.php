<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use App\Models\User;
use App\Repositories\Interfaces\InquiryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InquiryRepository implements InquiryRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Inquiry::query()
            ->with(['property:id,title,city,user_id', 'property.agent:id,name'])
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function getForAgent(User $agent, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Inquiry::query()
            ->with(['property:id,title,city,user_id'])
            ->whereHas('property', fn ($q) => $q->where('user_id', $agent->id))
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?Inquiry
    {
        return Inquiry::with([
            'property:id,title,city,address,user_id',
            'property.agent:id,name,email',
        ])->find($id);
    }

    public function create(array $data): Inquiry
    {
        return Inquiry::create($data);
    }

    public function updateStatus(Inquiry $inquiry, InquiryStatus $status): Inquiry
    {
        $inquiry->update(['status' => $status->value]);

        return $inquiry->fresh();
    }

    public function getRecentCount(int $days = 7): int
    {
        return Inquiry::recent($days)->count();
    }

    public function countByStatus(): array
    {
        return Inquiry::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    private function applyFilters(\Illuminate\Database\Eloquent\Builder $query, array $filters): void
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
            });
        }
    }
}
