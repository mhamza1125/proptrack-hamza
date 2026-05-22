<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        return $this->agentDashboard();
    }

    private function adminDashboard(): View
    {
        $stats        = $this->dashboardService->getAdminStats();
        $topListings  = $this->dashboardService->getTopPropertiesByInquiries(5);
        $recentInquiries = $this->dashboardService->getRecentInquiries(8);

        return view('admin.dashboard', compact('stats', 'topListings', 'recentInquiries'));
    }

    private function agentDashboard(): View
    {
        $user       = auth()->user();
        $stats      = $this->dashboardService->getAgentStats($user);
        $properties = $this->dashboardService->getAgentProperties($user);
        $recentInquiries = $this->dashboardService->getAgentRecentInquiries($user, 8);

        return view('agent.dashboard', compact('stats', 'properties', 'recentInquiries'));
    }
}
