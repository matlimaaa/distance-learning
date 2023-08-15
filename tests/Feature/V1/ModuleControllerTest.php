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

    public function testGetAllModules(): void
    {
        $course = Course::factory()->create();

        Module::factory(5)->for($course)->create();

        $response = $this->getJson(
            route(
                'courses.modules.index',
                [
                    'course' => $course->uuid
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

    public function testGetNotFoundModule(): void
    {
        $course = Course::factory()->create();

        $response = $this->getJson(
            route(
                'courses.modules.show',
                [
                    'course' => $course->uuid,
                    'module' => $this->faker->uuid(),
                ]
            )
        );

        $response->assertNotFound();
    }

    public function testGetModuleSuccessfully(): void
    {
        $course = Course::factory()->create();

        $module = Module::factory()->for($course)->create();

        $response = $this->getJson(
            route(
                'courses.modules.show',
                [
                    'course' => $course->uuid,
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
        $course = Course::factory()->create();

        $response = $this->postJson(
            route(
                'courses.modules.store',
                ['course' => $course->uuid],
            ),
            [
                'name' => $name,
            ]
        );

        $response->assertUnprocessable();
    }

    public function testValidationErrorWhenITryToUploadAnImageAsAModuleName(): void
    {
        $course = Course::factory()->create();

        $response = $this->postJson(
            route(
                'courses.modules.store',
                ['course' => $course->uuid],
            ),
            [
                'name' => UploadedFile::fake()->image('image.png'),
            ]
        );

        $response->assertUnprocessable();
    }

    public function testCreateANewModuleSucessfully(): void
    {
        $course = Course::factory()->create();

        $response = $this->postJson(
            route(
                'courses.modules.store',
                ['course' => $course->uuid],
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
        $course = Course::factory()->create();

        $module = Module::factory()->for($course)->create();

        $response = $this->putJson(
            route(
                'courses.modules.update',
                [
                    'course' => $course->uuid,
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
        $course = Course::factory()->create();

        $module = Module::factory()->for($course)->create();

        $response = $this->putJson(
            route(
                'courses.modules.update',
                [
                    'course' => $course->uuid,
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
        $course = Course::factory()->create();

        $response = $this->putJson(
            route(
                'courses.modules.update',
                [
                    'course' => $course->uuid,
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
        $course = Course::factory()->create();

        $module = Module::factory()->for($course)->create();

        $response = $this->deleteJson(
            route(
                'courses.modules.destroy',
                [
                    'course' => $course->uuid,
                    'module' => $module->uuid,
                ]
            )
        );

        $response->assertNoContent();
    }

    public function testTryToDeleteAModuleThatDoesNotExist(): void
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson(
            route(
                'courses.modules.destroy',
                [
                    'course' => $course->uuid,
                    'module' => $this->faker->uuid,
                ]
            )
        );

        $response->assertNotFound();
    }
}
