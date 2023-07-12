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
}