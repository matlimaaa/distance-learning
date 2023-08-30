<?php

namespace Tests\Feature\V1;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    use WithFaker;

    private Course $course;

    public function setUp(): void
    {
        parent::setUp();

        $this->course = Course::factory()->create();
    }

    public function testGetAllModules(): void
    {
        Module::factory(5)->for($this->course)->create();

        $response = $this->getJson(
            route(
                'courses.modules.index',
                [
                    'course' => $this->course->uuid
                ],
            )
        );
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                ]
            ]
        ]);
    }

    public function testNotFoundModule(): void
    {
        $response = $this->getJson(
            route(
                'courses.modules.show',
                [
                    'course' => $this->course->uuid,
                    'module' => $this->faker->uuid(),
                ]
            )
        );

        $response->assertNotFound();
    }

    public function testGetModuleSuccessfully(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $response = $this->getJson(
            route(
                'courses.modules.show',
                [
                    'course' => $this->course->uuid,
                    'module' => $module->uuid,
                ]
            )
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
            ]
        ]);
    }

    public static function inputDataProvider()
    {
        return [
            [null],
            [mt_rand(0, 9)],
            [true],
            [false],
        ];
    }

    #[DataProvider('inputDataProvider')]
    public function testValidateErrorWhenTryToCreateANewModule(mixed $name): void
    {
        $response = $this->postJson(
            route(
                'courses.modules.store',
                ['course' => $this->course->uuid],
            ),
            [
                'name' => $name,
            ]
        );

        $response->assertUnprocessable();
    }

    public function testValidationErrorWhenITryToUploadAnImageAsAModuleName(): void
    {
        $response = $this->postJson(
            route(
                'courses.modules.store',
                ['course' => $this->course->uuid],
            ),
            [
                'name' => UploadedFile::fake()->image('image.png'),
            ]
        );

        $response->assertUnprocessable();
    }

    public function testCreateANewModuleSucessfully(): void
    {
        $response = $this->postJson(
            route(
                'courses.modules.store',
                ['course' => $this->course->uuid],
            ),
            [
                'name' => $this->faker->name(),
            ]
        );

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
            ]
        ]);
    }

    #[DataProvider('inputDataProvider')]
    public function testValidateErrorWhenTryToUpdateModule(mixed $name): void
    {
        $module = Module::factory()->for($this->course)->create();

        $response = $this->putJson(
            route(
                'courses.modules.update',
                [
                    'course' => $this->course->uuid,
                    'module' => $module->uuid,
                ],
            ),
            [
                'name' => $name,
            ]
        );

        $response->assertUnprocessable();
    }

    public function testUpdateModuleSuccessfullly(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $response = $this->putJson(
            route(
                'courses.modules.update',
                [
                    'course' => $this->course->uuid,
                    'module' => $module->uuid,
                ],
            ),
            [
                'name' => $this->faker->name(),
            ]
        );

        $response->assertOk();
    }

    public function testTryToUpdateAModuleThatDoesNotExist(): void
    {
        $response = $this->putJson(
            route(
                'courses.modules.update',
                [
                    'course' => $this->course->uuid,
                    'module' => $this->faker->uuid()
                ]
            ),
            [
                'name' => $this->faker->name(),
            ],
        );

        $response->assertNotFound();
    }

    public function testDeleteAModuleSuccessfullly(): void
    {
        $module = Module::factory()->for($this->course)->create();

        $response = $this->deleteJson(
            route(
                'courses.modules.destroy',
                [
                    'course' => $this->course->uuid,
                    'module' => $module->uuid,
                ]
            )
        );

        $response->assertNoContent();
    }

    public function testTryToDeleteAModuleThatDoesNotExist(): void
    {
        $response = $this->deleteJson(
            route(
                'courses.modules.destroy',
                [
                    'course' => $this->course->uuid,
                    'module' => $this->faker->uuid,
                ]
            )
        );

        $response->assertNotFound();
    }
}
