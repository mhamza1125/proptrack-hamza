<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InquiryStatus;
use App\Jobs\SendInquiryNotificationJob;
use App\Models\Inquiry;
use App\Models\User;
use App\Repositories\Interfaces\InquiryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InquiryService
{
    public function __construct(
        private readonly InquiryRepositoryInterface $repository,
    ) {}

    public function getManagedInquiries(User $user, array $filters = []): LengthAwarePaginator
    {
        if ($user->hasRole('admin')) {
            return $this->repository->getAll($filters);
        }

        return $this->repository->getForAgent($user, $filters);
    }

    public function findForUser(int $id, User $user): ?Inquiry
    {
        $inquiry = $this->repository->findById($id);

        if (! $inquiry) {
            return null;
        }

        // Agents may only view inquiries on their own properties
        if ($user->hasRole('agent') && $inquiry->property?->user_id !== $user->id) {
            return null;
        }

        return $inquiry;
    }

    public function submit(array $data): Inquiry
    {
        $inquiry = $this->repository->create($data);

        // Load relationships before queuing so the job has full context
        $inquiry->load(['property', 'property.agent']);

        SendInquiryNotificationJob::dispatch($inquiry);

        return $inquiry;
    }

    public function updateStatus(Inquiry $inquiry, InquiryStatus $status): Inquiry
    {
        return $this->repository->updateStatus($inquiry, $status);
    }

    public function getStats(): array
    {
        return [
            'by_status'    => $this->repository->countByStatus(),
            'recent_count' => $this->repository->getRecentCount(7),
        ];
    }
}
