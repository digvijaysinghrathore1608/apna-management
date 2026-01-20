<?php

namespace App\Providers;

use App\Models\Service;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // e.g. \App\Models\Post::class => \App\Policies\PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('has-role', function ($user, $role) {
            return $user->roles()->where('name', $role)->exists();
        });
        Gate::define('has-service', function ($user, $service) {
            return $user->services()->where('name', $service)->exists();
        });

        Gate::define('has-role-service', function ($user, $role, $service) {
            return $user->services()->where('name', $service)->exists() && $user->roles()->where('name', $role)->exists();
        });
    }
}
