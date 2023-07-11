<?php

namespace App\Services\V1\Course;

use App\Repositories\V1\Course\CourseRepositoryContract;
use Illuminate\Support\Collection;

class CourseService implements CourseServiceContract
{
    /**
     * __construct
     *
     * @param CourseRepositoryContract $courseRepository
     */
    public function __construct(
        protected CourseRepositoryContract $courseRepository,
    ) {
    }

    /**
     * Get Courses
     *
     * @return Collection
     */
    public function getCourses(): Collection
    {
        return $this->courseRepository->getAllCourses();
    }
}