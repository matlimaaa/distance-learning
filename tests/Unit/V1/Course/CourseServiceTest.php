<?php

namespace Tests\Unit\V1\Course;

use App\Models\Course;
use App\Services\V1\Course\CourseServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    use WithFaker;

    private CourseServiceContract $courseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->courseService = app()->make(CourseServiceContract::class);
    }

    public function testGetAllCourses()
    {
        Course::factory(5)->create();

        $courses = $this->courseService->getCourses();

        $this->assertInstanceOf(Collection::class, $courses);
        $this->assertCount(5, $courses);
    }

    public function testStoreACourse()
    {
        $course = $this->courseService->store([
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
        ]);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertDatabaseCount('courses', 1);
        $this->assertDatabaseHas('courses', [
            'id' => $course->id
        ]);
    }

    public function testGetCourseSuccessfully(): void
    {
        $course = Course::factory()->create();

        $searchedCourse = $this->courseService->getCourse($course->uuid);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($course->id, $searchedCourse->id);
    }

    public function testDeleteACourseSuccessfullly(): void
    {
        $course = Course::factory()->create();

        $this->courseService->destroy($course->uuid);

        $this->assertSoftDeleted('courses', [
            'id' => $course->id
        ]);
    }

    public function testTryToDeleteACourseThatDoesNotExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->courseService->destroy($this->faker->uuid());
    }

    public function testUpdateCourseSuccessfullly(): void
    {
        $course = Course::factory()->create();

        $newName = $this->faker->unique()->name();

        $this->courseService->update(
            $course->uuid,
            [
                'name' => $newName,
                'description' => $this->faker->sentence(),
            ]
        );

        $this->assertDatabaseMissing('courses', [
            'name' => $course->name
        ]);
        
        $this->assertDatabaseHas('courses', [
            'name' => $newName
        ]);
    }

    public function testTryToUpdateACourseThatDoesNotExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->courseService->update(
            $this->faker->uuid(),
            [
                'name' => $this->faker->unique()->name(),
                'description' => $this->faker->sentence(),
            ]
        );
    }
}
