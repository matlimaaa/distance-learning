<?php

namespace App\Repositories\V1\Course;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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
        return Cache::remember('all_courses', 60 * 60, function () {
            return $this->course
                ->with('modules.lessons')
                ->get();
        });
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
    public function getCourseByUuid(string $uuid, bool $loadRelationships = true): Course
    {
        $query = $this->course->where('uuid', $uuid);

        if ($loadRelationships) {
            $query->with('modules.lessons');
        }

        return $query->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function deleteCourseByUuid(string $uuid): void
    {
        $course = $this->getCourseByUuid($uuid, false);
        $course->delete();
    }

    /**
     * @inheritDoc
     */
    public function updateCourse(string $uuid, array $attributes): void
    {
        $course = $this->getCourseByUuid($uuid, false);
        $course->update($attributes);
    }
}