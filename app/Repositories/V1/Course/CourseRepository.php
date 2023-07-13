<?php

namespace App\Repositories\V1\Course;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryContract
{
    /**
     * __construct
     *
     * @param Course $course
     */
    public function __construct(
        protected Course $course,
    ) {
    }
    
    /**
     * @inheritDoc
     */
    public function getAllCourses(): Collection
    {
        return $this->course->get();
    }
    
    /**
     * @inheritDoc
     */
    public function store(array $data): Course
    {
        return $this->course->create($data);
    }

    /**
     * @inheritDoc
     */
    public function getCourseByUuid(string $uuid): Course
    {
        return $this->course->where('uuid', $uuid)->firstOrFail();
    }
}