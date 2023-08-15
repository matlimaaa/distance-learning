<?php

namespace Tests\Feature\V1;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use WithFaker;

    public function testGetAllLessons(): void
    {
        $module = Module::factory()->forCourse()->create();

        Lesson::factory(5)->for($module)->create();

        $response = $this->getJson(
            route(
                'lessons.index',
                [
                    'module' => $module->uuid
                ],
            )
        );
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'video',
                    'description',
                ]
            ]
        ]);
    }

    public function testGetNotFoundLesson(): void
    {
        $module = Module::factory()->forCourse()->create();

        $response = $this->getJson(
            route(
                'lessons.show',
                [
                    'module' => $module->uuid,
                    'lesson' => $this->faker->uuid(),
                ]
            )
        );

        $response->assertNotFound();
    }

    public function testGetLesssonSuccessfully(): void
    {
        $module = Module::factory()->forCourse()->create();

        $lesson = Lesson::factory()->for($module)->create();

        $response = $this->getJson(
            route(
                'lessons.show',
                [
                    'module' => $module->uuid,
                    'lesson' => $lesson->uuid,
                ]
            )
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'video',
                'description',
            ]
        ]);
    }

    public static function inputDataProvider()
    {
        $randomString = `text`;

        return [
            [null, $randomString, $randomString],
            [mt_rand(0, 9), $randomString, $randomString],
            [true, $randomString, $randomString],
            [false, $randomString, $randomString],
            [$randomString, null, $randomString],
            [$randomString, mt_rand(0, 9), $randomString],
            [$randomString, true, $randomString],
            [$randomString, false, $randomString],
            [$randomString, $randomString, mt_rand(0, 9)],
            [$randomString, $randomString, true],
            [$randomString, $randomString, false],
        ];
    }

    #[DataProvider('inputDataProvider')]
    public function testValidateErrorWhenTryToCreateANewLesson(mixed $name, mixed $video, mixed $description): void
    {
        $module = Module::factory()->forCourse()->create();

        $response = $this->postJson(
            route(
                'lessons.store',
                ['module' => $module->uuid],
            ),
            [
                'name' => $name,
                'video' => $video,
                'description' => $description,
            ]
        );

        $response->assertUnprocessable();
    }

    public function testValidationErrorWhenITryToUploadAnImageForCreateANewLesson(): void
    {
        $module = Module::factory()->forCourse()->create();

        $response = $this->postJson(
            route(
                'lessons.store',
                ['module' => $module->uuid],
            ),
            [
                'name' => UploadedFile::fake()->image('image.png'),
                'video' => UploadedFile::fake()->image('image.png'),
                'description' => UploadedFile::fake()->image('image.png'),
            ]
        );

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'name' => [
                'The name field must be a string.',
                'The name field must be at least 5 kilobytes.'
            ],
            'video' => [
                'The video field must be a string.'
            ],
            'description' => [
                'The description field must be at least 3 kilobytes.'
            ]
        ]);
    }

    public function testCreateANewLessonSucessfully(): void
    {
        $module = Module::factory()->forCourse()->create();

        $response = $this->postJson(
            route(
                'lessons.store',
                ['module' => $module->uuid],
            ),
            [
                'name' => $this->faker->name(),
                'video' => $this->faker->url(),
                'description' => $this->faker->sentence(),
            ]
        );

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'video',
                'description',
            ]
        ]);
    }

    #[DataProvider('inputDataProvider')]
    public function testValidateErrorWhenTryToUpdateLesson(mixed $name, mixed $video, mixed $description): void
    {
        $module = Module::factory()->forCourse()->create();

        $lesson = Lesson::factory()->for($module)->create();
       
        $response = $this->putJson(
            route(
                'lessons.update',
                [
                    'module' => $module->uuid,
                    'lesson' => $lesson->uuid,
                ],
            ),
            [
                'name' => $name,
                'video' => $video,
                'description' => $description,
            ]
        );

        $response->assertUnprocessable();
    }

    public function testUpdateLessonSuccessfullly(): void
    {
        $module = Module::factory()->forCourse()->create();

        $lesson = Lesson::factory()->for($module)->create();
       
        $response = $this->putJson(
            route(
                'lessons.update',
                [
                    'module' => $module->uuid,
                    'lesson' => $lesson->uuid,
                ],
            ),
            [
                'name' => $this->faker->name(),
                'video' => $this->faker->url(),
                'description' => $this->faker->sentence(),
            ]
        );

        $response->assertOk();
    }

    public function testTryToUpdateALessonThatDoesNotExist(): void
    {
        $module = Module::factory()->forCourse()->create();

        Lesson::factory()->for($module)->create();
       
        $response = $this->putJson(
            route(
                'lessons.update',
                [
                    'module' => $module->uuid,
                    'lesson' => $this->faker->uuid(),
                ],
            ),
            [
                'name' => $this->faker->name(),
                'video' => $this->faker->url(),
                'description' => $this->faker->sentence(),
            ]
        );

        $response->assertNotFound();
    }

    public function testDeleteALessonSuccessfullly(): void
    {
        $module = Module::factory()->forCourse()->create();

        $lesson = Lesson::factory()->for($module)->create();
       
        $response = $this->deleteJson(
            route(
                'lessons.destroy',
                [
                    'module' => $module->uuid,
                    'lesson' => $lesson->uuid,
                ],
            )
        );

        $response->assertNoContent();
    }

    public function testTryToDeleteALessonThatDoesNotExist(): void
    {
        $module = Module::factory()->forCourse()->create();

        $lesson = Lesson::factory()->for($module)->create();
       
        $response = $this->deleteJson(
            route(
                'lessons.destroy',
                [
                    'module' => $module->uuid,
                    'lesson' => $this->faker->uuid,
                ],
            )
        );

        $response->assertNotFound();
    }
}
