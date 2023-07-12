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
}