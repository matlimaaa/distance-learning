<?php

namespace App\Services\V1\Course;

use App\Models\Course;
use Illuminate\Support\Collection;

interface CourseServiceContract
{
    /**
     * Get courses
     *
     * @return Collection
     */
    public function getCourses(): Collection;

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
    public function getCourse(string $uuid): Course;

    /**
     * Delete a course
     *
     * @param string $uuid
     * @return void
     */
    public function destroy(string $uuid): void;

    /**
     * Update course
     *
     * @param string $uuid
     * @param array $attributes
     * @return void
     */
    public function update(string $uuid, array $attributes): void;
}