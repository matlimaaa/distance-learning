<?php

namespace App\Repositories\V1\Course;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryContract
{
    /**
     * Get all courses
     *
     * @return Collection
     */
    public function getAllCourses(): Collection;

    /**
     * Create new course
     *
     * @param array $data
     * @return Course
     */
    public function store(array $data): Course;

    /**
     * Get course by uuid
     *
     * @param string $uuid
     * @return Course
     */
    public function getCourseByUuid(string $uuid): Course;

    /**
     * Delete course by uuid
     *
     * @param string $uuid
     * @return void
     */
    public function deleteCourseByUuid(string $uuid): void;

    /**
     * Update course
     *
     * @param string $uuid
     * @param array $attributes
     * @return void
     */
    public function updateCourse(string $uuid, array $attributes): void;
}