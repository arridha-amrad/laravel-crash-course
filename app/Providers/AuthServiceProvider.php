<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\UserDetails;
use App\Policies\UserDetailsPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        UserDetails::class => UserDetailsPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('update-delete-userDetails', [UserDetailsPolicy::class, 'updateAndDelete']);
    }
}
