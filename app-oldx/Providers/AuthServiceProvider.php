<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Permission;
use DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        
        foreach($this->getPermissions() as $permission) {

            Gate::define($permission->name, function ($user) use ($permission) {

                return $user->hasRole($permission->roles);

            });

        }
    }
    protected function getPermissions()
    {
        try {
            $permission = Permission::with('roles')->get();
        } catch (\Illuminate\Database\QueryException $e) {
            $permission = [];
        }
        return $permission;
    }
}
