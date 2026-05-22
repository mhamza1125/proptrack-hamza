<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInquiryNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries   = 3;
    public int $timeout = 30;

    public function __construct(
        public readonly Inquiry $inquiry,
    ) {}

    public function handle(): void
    {
        // Load relationships needed by the mailable template
        $this->inquiry->loadMissing(['property', 'property.agent']);

        $agentEmail = $this->inquiry->property?->agent?->email;

        if (! $agentEmail) {
            return;
        }

        Mail::to($agentEmail)->send(new NewInquiryMail($this->inquiry));
    }

    public function failed(\Throwable $exception): void
    {
        // Logged automatically by Laravel's failed_jobs table
        logger()->error('SendInquiryNotificationJob failed', [
            'inquiry_id' => $this->inquiry->id,
            'error'      => $exception->getMessage(),
        ]);
    }
}
