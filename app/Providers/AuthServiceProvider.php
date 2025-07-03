<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Ticket' => 'App\Policies\TicketPolicy',
        'App\Models\Client' => 'App\Policies\ClientPolicy',
        'App\Models\Device' => 'App\Policies\DevicePolicy',
        'App\Models\Invoice' => 'App\Policies\InvoicePolicy',
        'App\Models\ServicePlan' => 'App\Policies\ServicePlanPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Super Admin has all permissions
        Gate::before(function ($user) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Define specific gates for billing system modules
        Gate::define('manage-clients', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'billing-manager']);
        });

        Gate::define('manage-devices', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'network-manager']);
        });

        Gate::define('manage-billing', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'billing-manager']);
        });

        Gate::define('manage-payments', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'billing-manager']);
        });

        Gate::define('manage-service-plans', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin']);
        });

        Gate::define('view-reports', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'billing-manager', 'network-manager']);
        });

        Gate::define('manage-tickets', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'support-agent']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->hasRole('super-admin');
        });

        Gate::define('network-monitoring', function ($user) {
            return $user->hasAnyRole(['super-admin', 'admin', 'network-manager']);
        });
    }
}