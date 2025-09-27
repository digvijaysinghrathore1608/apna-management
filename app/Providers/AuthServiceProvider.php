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
        $this->registerPolicies();

        // Always allow super_admin for any ability
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true; // this skips all other checks
            }
        });

        // Dynamically define service permissions
        Service::with('roles')->get()->each(function ($service) {
            $service->roles->each(function ($role) use ($service) {
                $ability = $service->name . '.' . $role->name;

                Gate::define($ability, function ($user) use ($service, $role) {
                    return $user->hasServiceRole($service->name, $role->name);
                });
            });

            // Optional: generic "service only" ability
            Gate::define($service->name, function ($user) use ($service) {
                return $user->hasService($service->name);
            });
        });
    }
}
