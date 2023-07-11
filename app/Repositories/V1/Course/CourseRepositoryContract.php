<?php

namespace App\Repositories\V1\Course;

use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryContract
{
    /**
     * Get all courses
     *
     * @return Collection
     */
    public function getAllCourses(): Collection;
}