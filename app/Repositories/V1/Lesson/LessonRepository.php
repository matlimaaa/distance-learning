<?php

namespace App\Repositories\V1\Lesson;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository implements LessonRepositoryContract
{
    /**
     * __construct
     *
     * @param Lesson $lesson
     */
    public function __construct(
        protected Lesson $lesson,
    ) {
    }

    public function getLessonsByModule(int $courseId): Collection
    {
        return $this->lesson->where('course_id', $courseId)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function store(int $moduleId, array $data): Lesson
    {
        $data['module_id'] = $moduleId;

        return $this->lesson->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonByModule(string $lessonUuid, int $moduleId): Lesson
    {
        return $this->lesson->where([
            'uuid' => $lessonUuid,
            'module_id', $moduleId,
        ])->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonByUuid(string $lessonUuid): Lesson
    {
        return $this->lesson->where('uuid', $lessonUuid)->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function updateLesson(array $data, string $lessonUuid, int $moduleId): bool
    {
        $data['module_id'] = $moduleId;

        $lesson = $this->getLessonByUuid($lessonUuid);

        return $lesson->update($data);
    }

    /**
     * {@inheritDoc}
     */
    public function destroyLesson(string $lessonUuid): bool|null
    {
        $lesson = $this->getLessonByUuid($lessonUuid);

        return $lesson->delete();
    }
}