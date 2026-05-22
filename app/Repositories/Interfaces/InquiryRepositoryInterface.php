<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InquiryRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function getForAgent(User $agent, array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function findById(int $id): ?Inquiry;

    public function create(array $data): Inquiry;

    public function updateStatus(Inquiry $inquiry, InquiryStatus $status): Inquiry;

    public function getRecentCount(int $days = 7): int;

    public function countByStatus(): array;
}
