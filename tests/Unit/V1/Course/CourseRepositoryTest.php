<?php

namespace Tests\Unit\V1\Course;

use App\Models\Course;
use App\Repositories\V1\Course\CourseRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CourseRepositoryTest extends TestCase
{
    use WithFaker;

    private CourseRepositoryContract $courseRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->courseRepository = app()->make(CourseRepositoryContract::class);
    }

    public function testGetAllCourses()
    {
        Course::factory(5)->create();

        $courses = $this->courseRepository->getAllCourses();

        $this->assertInstanceOf(Collection::class, $courses);
        $this->assertCount(5, $courses);
        $this->assertTrue(Cache::has('all_courses'));
    }

    public function testStoreACourse()
    {
        $course = $this->courseRepository->store([
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

        $searchedCourse = $this->courseRepository->getCourseByUuid($course->uuid);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($course->id, $searchedCourse->id);
    }

    public function testDeleteACourseSuccessfullly(): void
    {
        $course = Course::factory()->create();

        $this->courseRepository->deleteCourseByUuid($course->uuid);

        $this->assertSoftDeleted('courses', [
            'id' => $course->id
        ]);
    }
}
