<?php

namespace Tests\Unit\V1\Module;

use App\Models\Course;
use App\Models\Module;
use App\Services\V1\Module\ModuleServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ModuleServiceTest extends TestCase
{
    use WithFaker;

    private Course $course;

    private ModuleServiceContract $moduleService;

    public function setUp(): void
    {
        parent::setUp();

        $this->course = Course::factory()->create();

        $this->moduleService = app()->make(ModuleServiceContract::class);
    }

    public function testGetAllModules(): void
    {
        Module::factory(2)->for($this->course)->create();

        $modules = $this->moduleService->getModulesByCourse($this->course->uuid);

        $this->assertInstanceOf(Collection::class, $modules);

        $modules->each(function ($module) {
            $this->assertInstanceOf(Module::class, $module);
        });
    }

    public function testNotFoundModule(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->moduleService->getModuleByCourse($this->course->uuid, $this->faker->uuid());
    }

    public function testGetModuleSuccessfully(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $moduleFound = $this->moduleService->getModuleByCourse($this->course->uuid, $module->uuid);
        
        $this->assertInstanceOf(Module::class, $moduleFound);

        $this->assertEquals($module->id, $moduleFound->id);
    }
}
