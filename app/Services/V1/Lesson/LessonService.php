<?php

namespace App\Services\V1\Lesson;

use App\Models\Lesson;
use App\Repositories\V1\Lesson\LessonRepositoryContract;
use App\Repositories\V1\Module\ModuleRepositoryContract;
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
        protected ModuleRepositoryContract $moduleRepository,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonsByModule(string $moduleUuid): Collection
    {
        $module = $this->moduleRepository->getModuleByUuid($moduleUuid);

        return $this->lessonRepository->getLessonsByModule($module->id);
    }

    /**
     * {@inheritDoc}
     */
    public function store(array $data, string $moduleUuid): Lesson
    {
        $module = $this->moduleRepository->getModuleByUuid($moduleUuid);

        return $this->lessonRepository->store($module->id, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function getLessonByModule(string $lessonUuid, string $moduleUuid): Lesson
    {
        $module = $this->moduleRepository->getModuleByUuid($moduleUuid);

        return $this->lessonRepository->getLessonByModule($lessonUuid, $module->id);
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
    public function updateLesson(array $data, string $lessonUuid, string $moduleUuid): bool
    {
        $module = $this->moduleRepository->getModuleByUuid($moduleUuid);

        return $this->lessonRepository->updateLesson($data, $lessonUuid, $module->id);
    }

    /**
     * {@inheritDoc}
     */
    public function destroyLesson(string $lessonUuid): bool|null
    {
        return $this->lessonRepository->destroyLesson($lessonUuid);
    }
}