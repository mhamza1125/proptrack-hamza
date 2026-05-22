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
use Illuminate\Support\Facades\Gate;
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
    }
}
