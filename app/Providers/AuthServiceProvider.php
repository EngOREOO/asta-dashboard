<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Super Admin can do anything
        Gate::before(function ($user, $ability) {
            return $user && method_exists($user, 'hasRole') && $user->hasRole('super-admin') ? true : null;
        });
    }
}


