<?php

namespace App\Services\V1\Lesson;

use App\Models\Lesson;
use Illuminate\Support\Collection;

interface LessonServiceContract
{
    /**
     * Get lessons by module
     *
     * @param int $moduleId
     * @return Collection
     */
    public function getLessonsByModule(int $moduleId): Collection;

    /**
     * store
     *
     * @param int $moduleId
     * @param array $data
     * @return Lesson
     */
    public function store(int $moduleId, array $data): Lesson;

    /**
     * getLessonByModule
     *
     * @param int $moduleId
     * @param string $lessonUuid
     * @return Lesson
     */
    public function getLessonByModule(int $moduleId, string $lessonUuid): Lesson;

    /**
     * Get lesson by uuid
     *
     * @param string $lessonUuid
     * @return Lesson
     */
    public function getLessonByUuid(string $lessonUuid): Lesson;

    /**
     * Update lesson
     *
     * @param int $moduleId
     * @param string $lessonUuid
     * @param array $data
     * @return bool
     */
    public function updateLesson(int $moduleId, string $lessonUuid, array $data): bool;

    /**
     * Destroy lesson
     *
     * @param string $lessonUuid
     * @return bool|null
     */
    public function destroyLesson(string $lessonUuid): bool|null;
}