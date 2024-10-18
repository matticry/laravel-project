<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Services\EmployeeService;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\EmployeeServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\ProfileServiceInterface;
use App\Services\Interfaces\ReportServiceInterface;
use App\Services\Interfaces\RoleServiceInterface;
use App\Services\Interfaces\ServiceServiceInterface;
use App\Services\Interfaces\TaskServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Interfaces\WorkOrderRepositoryInterface;
use App\Services\ProductService;
use App\Services\ProfileService;
use App\Services\ReportService;
use App\Services\RoleService;
use App\Services\ServiceService;
use App\Services\TaskService;
use App\Services\UserService;
use App\Services\WorkOrderService;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ServiceServiceInterface::class, ServiceService::class);
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(WorkOrderRepositoryInterface::class, WorkOrderService::class);
        $this->app->bind(ReportServiceInterface::class, ReportService::class);



        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
