<?php

namespace App\Providers;

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Compound;
use App\Models\Maintenance;
use App\Models\Tenant;
use App\Models\User;
use App\Observers\ApartmentObserver;
use App\Observers\BuildingObserver;
use App\Observers\CompoundObserver;
use App\Observers\MaintenanceObserver;
use App\Observers\TenantObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Apartment::observe(ApartmentObserver::class);
        Compound::observe(CompoundObserver::class);
        Maintenance::observe(MaintenanceObserver::class);
        Building::observe(BuildingObserver::class);
        Tenant::observe(TenantObserver::class);
        app()->setLocale('ar');
    }
}
