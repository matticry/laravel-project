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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('profile.update', function ($user) {
            return $user->hasPermission('profile.update');
        });
        Gate::define('profile.destroy', function ($user) {
            return $user->hasPermission('profile.destroy');
        });
        Gate::define('profile.store', function ($user) {
            return $user->hasPermission('profile.store');
        });
        //Permisos de las vistas
        Gate::define('view.index.profile', function ($user) {
            return $user->hasPermission('view.index.profile');
        });
        Gate::define('view.index.category', function ($user) {
            return $user->hasPermission('view.index.category');
        });
        Gate::define('view.index.product', function ($user) {
            return $user->hasPermission('view.index.product');
        });
        Gate::define('view.index.service', function ($user) {
            return $user->hasPermission('view.index.service');
        });
        Gate::define('view.index.employee', function ($user) {
            return $user->hasPermission('view.index.employee');
        });
        Gate::define('view.index.role', function ($user) {
            return $user->hasPermission('view.index.role');
        });
        //Permisos para roles
        Gate::define('role.update', function ($user) {
            return $user->hasPermission('role.update');
        });
        Gate::define('role.destroy', function ($user) {
            return $user->hasPermission('role.destroy');
        });
        Gate::define('role.store', function ($user) {
            return $user->hasPermission('role.store');
        });
        //Permisos para productos
        Gate::define('product.update', function ($user) {
            return $user->hasPermission('product.update');
        });
        Gate::define('role.store', function ($user) {
            return $user->hasPermission('role.store');
        });
        Gate::define('product.destroy', function ($user) {
            return $user->hasPermission('product.destroy');
        });
        Gate::define('product.store', function ($user) {
            return $user->hasPermission('product.store');
        });
        //Permisos para categorias
        Gate::define('category.update', function ($user) {
            return $user->hasPermission('category.update');
        });
        Gate::define('category.destroy', function ($user) {
            return $user->hasPermission('category.destroy');
        });
        Gate::define('category.store', function ($user) {
            return $user->hasPermission('category.store');
        });
        //Permisos para servicios
        Gate::define('service.update', function ($user) {
            return $user->hasPermission('service.update');
        });
        Gate::define('service.destroy', function ($user) {
            return $user->hasPermission('service.destroy');
        });
        Gate::define('service.store', function ($user) {
            return $user->hasPermission('service.store');
        });
        //Permisos para empleados
        Gate::define('employee.update', function ($user) {
            return $user->hasPermission('employee.update');
        });
        Gate::define('employee.destroy', function ($user) {
            return $user->hasPermission('employee.destroy');
        });
        Gate::define('employee.store', function ($user) {
            return $user->hasPermission('employee.store');
        });
        Gate::define('button.add.client', function ($user) {
            return $user->hasPermission('button.add.client');
        });
        Gate::define('button.add.service', function ($user) {
            return $user->hasPermission('button.add.service');
        });
        Gate::define('button.upload.signature', function ($user) {
            return $user->hasPermission('button.upload.signature');
        });
        Gate::define('button.signature', function ($user) {
            return $user->hasPermission('button.signature');
        });


    }
}
