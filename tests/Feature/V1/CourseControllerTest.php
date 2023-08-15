<?php

namespace Tests\Feature\V1;

use App\Models\Course;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use WithFaker;

    public function testGetAllCourses(): void
    {
        Course::factory(5)->create();
        $response = $this->getJson(route('courses.index'));

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'description',
                    'date',
                    'modules',
                ]
            ]
        ]);
    }

    public function testGetNotFoundCourse(): void
    {
        $response = $this->getJson(
            route(
                'courses.show',
                [
                    'uuid' => $this->faker->uuid()
                ]
            )
        );

        $response->assertNotFound();
    }

    public function testGetCourseSuccessfully(): void
    {
        $course = Course::factory()->create();

        $response = $this->getJson(
            route(
                'courses.show',
                [
                    'uuid' => $course->uuid
                ]
            )
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'description',
                'date',
            ]
        ]);
    }

    public static function inputDataProvider()
    {
        $randomNameOrDescription = `text`;

        return [
            [null, $randomNameOrDescription],
            [mt_rand(0, 9), $randomNameOrDescription],
            [$randomNameOrDescription, mt_rand(0, 9)],
            [true, mt_rand(0, 9)],
            [false, mt_rand(0, 9)],
            [$randomNameOrDescription, true],
            [$randomNameOrDescription, false],
        ];
    }

    #[DataProvider('inputDataProvider')]
    public function testValidateErrorWhenTryToCreateANewCourse(mixed $name, mixed $description): void
    {
        $response = $this->postJson(
            route('courses.store'),
            [
                'name' => $name,
                'description' => $description,
            ]
        );

        $response->assertUnprocessable();
    }

    public function testValidateErrorWhenITryToSubmitAnExistingName(): void
    {
        $response = $this->postJson(
            route('courses.store'),
            [
                'name' => (Course::factory()->create())->name,
                'description' => $this->faker->sentence(),
            ]
        );

        $response->assertUnprocessable();
    }

    public function testValidationErrorWhenITryToUploadAnImageAsACourseName(): void
    {
        $response = $this->postJson(
            route('courses.store'),
            [
                'name' => UploadedFile::fake()->image('image.png'),
                'description' => $this->faker->sentence(),
            ]
        );

        $response->assertUnprocessable();
    }

    public function testValidationErrorWhenITryToUploadAnImageAsACourseDescription(): void
    {
        $response = $this->postJson(
            route('courses.store'),
            [
                'name' => $this->faker->sentence(),
                'description' => UploadedFile::fake()->image('image.png'),
            ]
        );

        $response->assertUnprocessable();
    }

    public function testCreateANewCourseSucessfully(): void
    {
        $response = $this->postJson(
            route('courses.store'),
            [
                'name' => $this->faker->name(),
                'description' => $this->faker->sentence(),
            ]
        );

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'description',
                'date',
            ]
        ]);
    }

    #[DataProvider('inputDataProvider')]
    public function testValidateErrorWhenTryToUpdateCourse(mixed $name, mixed $description): void
    {
        $course = Course::factory()->create();

        $response = $this->putJson(
            route(
                'courses.update',
                [
                    'uuid' => $course->uuid
                ]
            ),
            [
                'name' => $name,
                'description' => $description,
            ],
        );

        $response->assertUnprocessable();
    }

    public function testUpdateCourseSuccessfullly(): void
    {
        $course = Course::factory()->create();

        $response = $this->putJson(
            route(
                'courses.update',
                [
                    'uuid' => $course->uuid
                ]
            ),
            [
                'name' => $this->faker->name(),
                'description' => $this->faker->sentence(),
            ],
        );

        $response->assertOk();
    }

    public function testTryToUpdateACourseThatDoesNotExist(): void
    {
        $response = $this->putJson(
            route(
                'courses.update',
                [
                    'uuid' => $this->faker->uuid()
                ]
            ),
            [
                'name' => $this->faker->name(),
                'description' => $this->faker->sentence(),
            ],
        );

        $response->assertNotFound();
    }

    public function testDeleteACourseSuccessfullly(): void
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson(
            route(
                'courses.destroy',
                [
                    'uuid' => $course->uuid
                ]
            )
        );

        $response->assertNoContent();
    }

    public function testTryToDeleteACourseThatDoesNotExist(): void
    {
        $response = $this->deleteJson(
            route(
                'courses.destroy',
                [
                    'uuid' => $this->faker->uuid()
                ]
            )
        );

        $response->assertNotFound();
    }

    public function testValidateCacheExistence(): void
    {
        Course::factory(5)->create();
        $response = $this->getJson(route('courses.index'));

        $response->assertOk();
        $this->assertTrue(Cache::has('all_courses'));
    }
}
