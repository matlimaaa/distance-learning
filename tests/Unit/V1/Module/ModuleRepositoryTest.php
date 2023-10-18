<?php

namespace Tests\Unit\V1\Module;

use App\Models\Course;
use App\Models\Module;
use App\Repositories\V1\Module\ModuleRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ModuleRepositoryTest extends TestCase
{
    use WithFaker;

    private Course $course;

    private ModuleRepositoryContract $moduleRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->course = Course::factory()->create();

        $this->moduleRepository = app()->make(ModuleRepositoryContract::class);
    }

    public function testGetAllModules(): void
    {
        Module::factory(2)->for($this->course)->create();

        $modules = $this->moduleRepository->getModulesByCourse($this->course->id);

        $this->assertInstanceOf(Collection::class, $modules);

        $modules->each(function ($module) {
            $this->assertInstanceOf(Module::class, $module);
        });
    }
}
