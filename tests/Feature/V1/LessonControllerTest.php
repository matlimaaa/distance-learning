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

    private Module $module;

    public function setUp(): void
    {
        parent::setUp();

        $this->module = Module::factory()->forCourse()->create();
    }

    public function testGetAllLessons(): void
    {
        Lesson::factory(5)->for($this->module)->create();

        $response = $this->getJson(
            route(
                'lessons.index',
                [
                    'module' => $this->module->uuid
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

    public function testNotFoundLesson(): void
    {
        $response = $this->getJson(
            route(
                'lessons.show',
                [
                    'module' => $this->module->uuid,
                    'lesson' => $this->faker->uuid(),
                ]
            )
        );

        $response->assertNotFound();
    }

    public function testGetLesssonSuccessfully(): void
    {
        $lesson = Lesson::factory()->for($this->module)->create();

        $response = $this->getJson(
            route(
                'lessons.show',
                [
                    'module' => $this->module->uuid,
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
        $response = $this->postJson(
            route(
                'lessons.store',
                ['module' => $this->module->uuid],
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
        $response = $this->postJson(
            route(
                'lessons.store',
                ['module' => $this->module->uuid],
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
        $response = $this->postJson(
            route(
                'lessons.store',
                ['module' => $this->module->uuid],
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
        $lesson = Lesson::factory()->for($this->module)->create();
       
        $response = $this->putJson(
            route(
                'lessons.update',
                [
                    'module' => $this->module->uuid,
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
        $lesson = Lesson::factory()->for($this->module)->create();
       
        $response = $this->putJson(
            route(
                'lessons.update',
                [
                    'module' => $this->module->uuid,
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
        Lesson::factory()->for($this->module)->create();
       
        $response = $this->putJson(
            route(
                'lessons.update',
                [
                    'module' => $this->module->uuid,
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
        $lesson = Lesson::factory()->for($this->module)->create();
       
        $response = $this->deleteJson(
            route(
                'lessons.destroy',
                [
                    'module' => $this->module->uuid,
                    'lesson' => $lesson->uuid,
                ],
            )
        );

        $response->assertNoContent();
    }

    public function testTryToDeleteALessonThatDoesNotExist(): void
    {
        Lesson::factory()->for($this->module)->create();
       
        $response = $this->deleteJson(
            route(
                'lessons.destroy',
                [
                    'module' => $this->module->uuid,
                    'lesson' => $this->faker->uuid,
                ],
            )
        );

        $response->assertNotFound();
    }
}
