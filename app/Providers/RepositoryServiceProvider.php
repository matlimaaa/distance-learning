<?php

namespace App\Providers;

use App\Repositories\V1\Course\{
    CourseRepository,
    CourseRepositoryContract
};
use App\Repositories\V1\Module\{
    ModuleRepository,
    ModuleRepositoryContract
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CourseRepositoryContract::class,
            CourseRepository::class
        );
        $this->app->bind(
            ModuleRepositoryContract::class,
            ModuleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
