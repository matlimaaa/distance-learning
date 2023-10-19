<?php

namespace Tests\Unit\V1\Lesson;

use App\Models\Lesson;
use App\Models\Module;
use App\Repositories\V1\Lesson\LessonRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LessonRepositoryTest extends TestCase
{
    use WithFaker;

    private Module $module;

    private LessonRepositoryContract $lessonRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->module = Module::factory()->forCourse()->create();

        $this->lessonRepository = app()->make(LessonRepositoryContract::class);
    }

    public function testGetAllLessonsByModule(): void
    {
        Lesson::factory(2)->for($this->module)->create();

        $lessons = $this->lessonRepository->getLessonsByModule($this->module->id);

        $this->assertInstanceOf(Collection::class, $lessons);

        $lessons->each(function ($lesson) {
            $this->assertInstanceOf(Lesson::class, $lesson);
        });
    }

    public function testNotFoundLesson(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->lessonRepository->getLessonByModule(
            $this->faker->uuid(),
            $this->module->id
        );
    }

    public function testGetLesssonSuccessfully(): void
    {
        $lesson = Lesson::factory()->for($this->module)->create();

        $lessonFound = $this->lessonRepository->getLessonByModule(
            $lesson->uuid,
            $this->module->id
        );

        $this->assertInstanceOf(Lesson::class, $lessonFound);

        $this->assertEquals($lesson->id, $lessonFound->id);
    }

    public function testCreateANewLessonSucessfully(): void
    {
        $newLesson = $this->lessonRepository->store(
            $this->module->id,
            [
                'name' => $this->faker->name(),
                'video' => $this->faker->url(),
                'description' => $this->faker->sentence(),
            ],
        );

        $this->assertInstanceOf(Lesson::class, $newLesson);
        $this->assertDatabaseHas('lessons', [
            'id' => $newLesson->id
        ]);
    }

    public function testUpdateLessonSuccessfullly(): void
    {
        $lesson = Lesson::factory()->for($this->module)->create();

        $newName = $this->faker->name();
        $video = $this->faker->url();
        $description = $this->faker->sentence();

        $successfullyUpdated = $this->lessonRepository->updateLesson(
            [
                'name' => $newName,
                'video' => $video,
                'description' => $description,
            ],
            $lesson->uuid,
            $this->module->id
        );

        $this->assertTrue($successfullyUpdated);
        $this->assertDatabaseMissing('lessons', [
            'name' => $lesson->name,
            'video' => $lesson->video,
            'description' => $lesson->description,
        ]);
        $this->assertDatabaseHas('lessons', [
            'name' => $newName,
            'video' => $video,
            'description' => $description,
        ]);
    }
}
