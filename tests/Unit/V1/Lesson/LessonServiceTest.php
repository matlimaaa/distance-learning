<?php

namespace Tests\Unit\V1\Lesson;

use App\Models\Lesson;
use App\Models\Module;
use App\Services\V1\Lesson\LessonServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LessonServiceTest extends TestCase
{
    use WithFaker;

    private Module $module;

    private LessonServiceContract $lessonService;

    public function setUp(): void
    {
        parent::setUp();

        $this->module = Module::factory()->forCourse()->create();

        $this->lessonService = app()->make(LessonServiceContract::class);
    }

    public function testGetAllLessons(): void
    {
        Lesson::factory(2)->for($this->module)->create();

        $lessons = $this->lessonService->getLessonsByModule($this->module->uuid);

        $this->assertInstanceOf(Collection::class, $lessons);

        $lessons->each(function ($lesson) {
            $this->assertInstanceOf(Lesson::class, $lesson);
        });
    }

    public function testNotFoundLesson(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->lessonService->getLessonByModule(
            $this->faker->uuid(),
            $this->module->uuid
        );
    }

    public function testGetLesssonSuccessfully(): void
    {
        $lesson = Lesson::factory()->for($this->module)->create();

        $lessonFound = $this->lessonService->getLessonByModule(
            $lesson->uuid,
            $this->module->uuid
        );

        $this->assertInstanceOf(Lesson::class, $lessonFound);

        $this->assertEquals($lesson->id, $lessonFound->id);
    }

    public function testCreateANewLessonSucessfully(): void
    {
        $newLesson = $this->lessonService->store(
            [
                'name' => $this->faker->name(),
                'video' => $this->faker->url(),
                'description' => $this->faker->sentence(),
            ],
            $this->module->uuid,
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

        $successfullyUpdated = $this->lessonService->updateLesson(
            [
                'name' => $newName,
                'video' => $video,
                'description' => $description,
            ],
            $lesson->uuid,
            $this->module->uuid
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

    public function testTryToUpdateALessonThatDoesNotExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->lessonService->updateLesson(
            [
                'name' => $this->faker->name(),
                'video' => $this->faker->url(),
                'description' => $this->faker->sentence(),
            ],
            $this->faker->uuid,
            $this->module->uuid
        );
    }

    public function testDeleteAModuleSuccessfullly(): void
    {
        $lesson = Lesson::factory()->for($this->module)->create();

        $result = $this->lessonService->destroyLesson($lesson->uuid);

        $this->assertTrue($result);
        $this->assertSoftDeleted('lessons', [
            'id' => $lesson->id,
            'name' => $lesson->name,
            'video' => $lesson->video,
            'description' => $lesson->description,
        ]);
    }
}
