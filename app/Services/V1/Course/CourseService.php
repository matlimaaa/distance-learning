<?php

namespace App\Services\V1\Course;

use App\Models\Course;
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
     * @inheritDoc
     */
    public function getCourses(): Collection
    {
        return $this->courseRepository->getAllCourses();
    }

    /**
     * @inheritDoc
     */
    public function store(array $data): Course
    {
        return $this->courseRepository->store($data);
    }

    /**
     * @inheritDoc
     */
    public function getCourse(string $uuid): Course
    {
        return $this->courseRepository->getCourseByUuid($uuid);
    }

    /**
     * @inheritDoc
     */
    public function destroy(string $uuid): void
    {
        $this->courseRepository->deleteCourseByUuid($uuid);
    }

    /**
     * @inheritDoc
     */
    public function update(string $uuid, array $attributes): void
    {
        $this->courseRepository->updateCourse($uuid, $attributes);
    }
}