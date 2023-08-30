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
    
    public function testCreateANewModuleSucessfully(): void
    {
        $newModule = $this->moduleService->store(
            [
                'name' => $this->faker->name(),
            ],
            $this->course->uuid,
        );

        $this->assertInstanceOf(Module::class, $newModule);
        $this->assertDatabaseHas('modules', [
            'id' => $newModule->id
        ]);
    }

    public function testUpdateModuleSuccessfullly(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $newName = str_shuffle($this->faker->name());

        $successfullyUpdated = $this->moduleService->updateModule(
            [
                'name' => $newName,
            ],
            $this->course->uuid,
            $module->uuid,
        );

        $this->assertTrue($successfullyUpdated);
        $this->assertDatabaseMissing('modules', [
            'name' => $module->name,
        ]);
        $this->assertDatabaseHas('modules', [
            'name' => $newName,
        ]);
    }

    public function testTryToUpdateAModuleThatDoesNotExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->moduleService->updateModule(
            [
                'name' => $this->faker->name(),
            ],
            $this->course->uuid,
            $this->faker->uuid(),
        );
    }

    public function testDeleteAModuleSuccessfullly(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $result = $this->moduleService->destroyModule($module->uuid);
        
        $this->assertTrue($result);
        $this->assertSoftDeleted('modules', [
            'id' => $module->id,
            'name' => $module->name,
        ]);
    }
}
