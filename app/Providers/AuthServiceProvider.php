<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!app()->runningInConsole() || app()->runningUnitTests()) {
            Gate::define('only-admin', function ($user) {
                return $user->hasRole('admin');
            });
            Gate::define('only-secretary', function ($user) {
                return $user->hasRole('secretary');
            });
            Gate::define('only-trainer', function ($user) {
                return $user->hasRole('trainer');
            });
            Gate::define('only-trainee', function ($user) {
                return $user->hasRole('trainee');
            });
            Gate::define('only-admin-and-secretary', function ($user) {
                return $user->hasAnyRoles(['admin', 'secretary']);
            });
            Gate::define('only-secretary-and-trainer', function ($user) {
                return $user->hasAnyRoles(['secretary', 'trainer']);
            });
            Gate::define('only-secretary-and-trainee', function ($user) {
                return $user->hasAnyRoles(['secretary', 'trainee']);
            });
        }

        //
    }
}
