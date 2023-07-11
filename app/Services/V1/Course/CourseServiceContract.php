<?php

namespace App\Services\V1\Course;

use Illuminate\Support\Collection;

interface CourseServiceContract
{
    /**
     * Get courses
     *
     * @return Collection
     */
    public function getCourses(): Collection;
}