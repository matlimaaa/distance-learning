<?php

namespace App\Services\V1\Lesson;

use App\Models\Lesson;
use App\Repositories\V1\Lesson\LessonRepositoryContract;
use Illuminate\Support\Collection;

class LessonService implements LessonServiceContract
{
    /**
     * __construct
     *
     * @param LessonRepositoryContract $lessonRepository
     */
    public function __construct(
        protected LessonRepositoryContract $lessonRepository,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonsByModule(int $moduleId): Collection
    {
        return $this->lessonRepository->getLessonsByModule($moduleId);
    }

    /**
     * {@inheritDoc}
     */
    public function store(int $moduleId, array $data): Lesson
    {
        return $this->lessonRepository->store($moduleId, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonByModule(int $moduleId, string $lessonUuid): Lesson
    {
        return $this->lessonRepository->getLessonByModule($moduleId, $lessonUuid);
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonByUuid(string $lessonUuid): Lesson
    {
        return $this->lessonRepository->getLessonByUuid($lessonUuid);
    }

    /**
     * {@inheritDoc}
     */
    public function updateLesson(int $moduleId, string $lessonUuid, array $data): bool
    {
        return $this->lessonRepository->updateLesson($moduleId, $lessonUuid, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function destroyLesson(string $lessonUuid): bool|null
    {
        return $this->lessonRepository->destroyLesson($lessonUuid);
    }
}