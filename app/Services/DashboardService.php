<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InquiryStatus;
use App\Enums\PropertyStatus;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    // ── Admin ────────────────────────────────────────────────────────────────

    public function getAdminStats(): array
    {
        $propertiesByStatus = Property::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $inquiriesByStatus = Inquiry::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total_properties'   => Property::count(),
            'active_properties'  => (int) ($propertiesByStatus[PropertyStatus::Active->value] ?? 0),
            'total_inquiries'    => Inquiry::count(),
            'new_inquiries_week' => Inquiry::recent(7)->count(),
            'properties_by_status' => $propertiesByStatus,
            'inquiries_by_status'  => $inquiriesByStatus,
        ];
    }

    public function getTopPropertiesByInquiries(int $limit = 5): Collection
    {
        return Property::query()
            ->withCount('inquiries')
            ->with(['agent:id,name'])
            ->orderByDesc('inquiries_count')
            ->limit($limit)
            ->get(['id', 'title', 'city', 'status', 'type', 'user_id']);
    }

    public function getRecentInquiries(int $limit = 8): Collection
    {
        return Inquiry::query()
            ->with(['property:id,title,user_id', 'property.agent:id,name'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    // ── Agent ─────────────────────────────────────────────────────────────────

    public function getAgentStats(User $agent): array
    {
        $propertiesByStatus = Property::query()
            ->where('user_id', $agent->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $inquiriesByStatus = Inquiry::query()
            ->whereHas('property', fn ($q) => $q->where('user_id', $agent->id))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total_properties'   => Property::where('user_id', $agent->id)->count(),
            'active_properties'  => (int) ($propertiesByStatus[PropertyStatus::Active->value] ?? 0),
            'total_inquiries'    => array_sum($inquiriesByStatus->toArray()),
            'new_inquiries_week' => Inquiry::recent(7)
                ->whereHas('property', fn ($q) => $q->where('user_id', $agent->id))
                ->count(),
            'properties_by_status' => $propertiesByStatus,
            'inquiries_by_status'  => $inquiriesByStatus,
        ];
    }

    public function getAgentProperties(User $agent): Collection
    {
        return Property::query()
            ->where('user_id', $agent->id)
            ->withCount([
                'inquiries',
                'inquiries as active_inquiries_count' => function ($q) {
                    $q->whereIn('status', [
                        InquiryStatus::New->value,
                        InquiryStatus::InReview->value,
                    ]);
                },
            ])
            ->latest()
            ->get();
    }

    public function getAgentRecentInquiries(User $agent, int $limit = 8): Collection
    {
        return Inquiry::query()
            ->with(['property:id,title'])
            ->whereHas('property', fn ($q) => $q->where('user_id', $agent->id))
            ->latest()
            ->limit($limit)
            ->get();
    }
}
