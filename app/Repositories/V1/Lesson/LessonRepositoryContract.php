<?php

namespace App\Repositories\V1\Lesson;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

interface LessonRepositoryContract
{
    /**
     * getLessonsByModule
     *
     * @param int $courseId
     * @return Collection
     */
    public function getLessonsByModule(int $courseId): Collection;
    
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
     * @param string $lessonUuid
     * @param int $moduleId
     * @return Lesson
     */
    public function getLessonByModule(string $lessonUuid, int $moduleId): Lesson;
    
    /**
     * getLessonByUuid
     *
     * @param string $lessonUuid
     * @return Lesson
     */
    public function getLessonByUuid(string $lessonUuid): Lesson;
    
    /**
     * updateLesson
     *
     * @param array $data
     * @param string $lessonUuid
     * @param int $moduleId
     * @return bool
     */
    public function updateLesson(array $data, string $lessonUuid, int $moduleId): bool;
    
    /**
     * destroyLesson
     *
     * @param string $lessonUuid
     * @return bool|null
     */
    public function destroyLesson(string $lessonUuid): bool|null;

}