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

    public function testNotFoundModule(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->moduleRepository->getModuleByCourse($this->course->id, $this->faker->uuid());
    }

    public function testGetModuleSuccessfully(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $moduleFound = $this->moduleRepository->getModuleByCourse($this->course->id, $module->uuid);
        
        $this->assertInstanceOf(Module::class, $moduleFound);

        $this->assertEquals($module->id, $moduleFound->id);
    }
    
    public function testCreateANewModuleSucessfully(): void
    {
        $newModule = $this->moduleRepository->store(
            $this->course->id,
            [
                'name' => $this->faker->name(),
            ],
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

        $successfullyUpdated = $this->moduleRepository->updateModule(
            $this->course->id,
            $module->uuid,
            [
                'name' => $newName,
            ],
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

        $this->moduleRepository->updateModule(
            $this->course->id,
            $this->faker->uuid(),
            [
                'name' => $this->faker->name(),
            ],
        );
    }

    public function testDeleteAModuleSuccessfullly(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $result = $this->moduleRepository->destroyModule($module->uuid);
        
        $this->assertTrue($result);
        $this->assertSoftDeleted('modules', [
            'id' => $module->id,
            'name' => $module->name,
        ]);
    }
}
