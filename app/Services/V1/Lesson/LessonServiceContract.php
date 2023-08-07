<?php

namespace App\Services\V1\Lesson;

use App\Models\Lesson;
use Illuminate\Support\Collection;

interface LessonServiceContract
{
    /**
     * Get lessons by module
     *
     * @param string $moduleUuid
     * @return Collection
     */
    public function getLessonsByModule(string $moduleUuid): Collection;

    /**
     * store
     *
     * @param array $data
     * @param string $moduleUuid
     * @return Lesson
     */
    public function store(array $data, string $moduleUuid): Lesson;

    /**
     * getLessonByModule
     *
     * @param string $lessonUuid
     * @param string $moduleUuid
     * @return Lesson
     */
    public function getLessonByModule(string $lessonUuid, string $moduleUuid): Lesson;

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
     * @param array $data
     * @param string $lessonUuid
     * @param string $moduleUuid
     * @return bool
     */
    public function updateLesson(array $data, string $lessonUuid, string $moduleUuid): bool;

    /**
     * Destroy lesson
     *
     * @param string $lessonUuid
     * @return bool|null
     */
    public function destroyLesson(string $lessonUuid): bool|null;
}