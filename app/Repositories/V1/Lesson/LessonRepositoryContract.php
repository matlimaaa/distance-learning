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
     * @param int $moduleId
     * @param string $lessonUuid
     * @return Lesson
     */
    public function getLessonByModule(int $moduleId, string $lessonUuid): Lesson;
    
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
     * @param int $moduleId
     * @param string $lessonUuid
     * @param array $data
     * @return bool
     */
    public function updateLesson(int $moduleId, string $lessonUuid, array $data): bool;
    
    /**
     * destroyLesson
     *
     * @param string $lessonUuid
     * @return bool|null
     */
    public function destroyLesson(string $lessonUuid): bool|null;

}