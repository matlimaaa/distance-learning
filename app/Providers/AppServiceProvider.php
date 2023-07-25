<?php

namespace App\Providers;

use App\Services\V1\Course\{
    CourseService,
    CourseServiceContract
};
use App\Services\V1\Lesson\{
    LessonService,
    LessonServiceContract
};
use App\Services\V1\Module\{
    ModuleService,
    ModuleServiceContract
};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CourseServiceContract::class, CourseService::class);
        $this->app->bind(ModuleServiceContract::class, ModuleService::class);
        $this->app->bind(LessonServiceContract::class, LessonService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
