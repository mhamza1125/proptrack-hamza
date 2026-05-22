<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\InquiryStatus;
use App\Http\Requests\Inquiry\StoreInquiryRequest;
use App\Http\Requests\Inquiry\UpdateInquiryStatusRequest;
use App\Models\Inquiry;
use App\Services\InquiryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function __construct(
        private readonly InquiryService $inquiryService,
    ) {}

    public function index(Request $request): View
    {
        $filters       = $request->only(['status', 'search']);
        $inquiries     = $this->inquiryService->getManagedInquiries(auth()->user(), $filters);
        $statuses      = InquiryStatus::cases();
        $statusCounts  = auth()->user()->hasRole('admin')
            ? $this->inquiryService->getStats()['by_status']
            : [];

        return view('inquiries.index', compact('inquiries', 'filters', 'statuses', 'statusCounts'));
    }

    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        $this->inquiryService->submit($request->validated());

        return redirect()
            ->back()
            ->with('inquiry_sent', true);
    }

    public function show(int $id): View|RedirectResponse
    {
        $inquiry = $this->inquiryService->findForUser($id, auth()->user());

        if (! $inquiry) {
            return redirect()
                ->route('inquiries.index')
                ->with('error', 'Inquiry not found or access denied.');
        }

        $statuses = InquiryStatus::cases();

        return view('inquiries.show', compact('inquiry', 'statuses'));
    }

    public function updateStatus(UpdateInquiryStatusRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $status = InquiryStatus::from($request->validated('status'));

        $this->inquiryService->updateStatus($inquiry, $status);

        return back()->with('success', "Status updated to \"{$status->label()}\".");
    }
}
