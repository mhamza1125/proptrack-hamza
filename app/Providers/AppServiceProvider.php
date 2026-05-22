<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Policies\InquiryPolicy;
use App\Policies\PropertyPolicy;
use App\Repositories\Interfaces\InquiryRepositoryInterface;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use App\Repositories\InquiryRepository;
use App\Repositories\PropertyRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PropertyRepositoryInterface::class,
            PropertyRepository::class,
        );

        $this->app->bind(
            InquiryRepositoryInterface::class,
            InquiryRepository::class,
        );
    }

    public function boot(): void
    {
        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(Inquiry::class, InquiryPolicy::class);

        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        // Public listing endpoints — 60 requests/minute per IP
        RateLimiter::for('api.listings', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Inquiry submission — 10 requests/minute per IP (anti-spam)
        RateLimiter::for('api.inquiries', function (Request $request) {
            return Limit::perMinute(10)->by(
                optional($request->user())->id ?? $request->ip()
            );
        });

        // Auth endpoint — 5 attempts/minute per IP (brute-force protection)
        RateLimiter::for('api.auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
