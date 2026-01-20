<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];
    
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('root')) {
                return true;
            }

            return null;
        });

        $this->defineCustomGates();
    }

    protected function defineCustomGates(): void
    {
        Gate::define('manage-system-roles', function ($user) {
            return $user->hasRole('root');
        });
        Gate::define('manage-business-roles', function ($user) {
            return $user->hasAnyPermission([
                'admin.role.create',
                'admin.role.update',
                'admin.role.delete',
            ]);
        });
        Gate::define('manage-permissions', function ($user) {
            return $user->hasAnyPermission([
                'admin.permission.create',
                'admin.permission.update',
                'admin.permission.delete',
            ]);
        });
        Gate::define('view-branch-data', function ($user, $branchId) {
            if ($user->hasRole(['root', 'admin'])) {
                return true;
            }
            return $user->branch_id === $branchId;
        });
    }
}
