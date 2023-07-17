<?php

namespace App\Services\V1\Module;

use App\Models\Module;
use App\Repositories\V1\Module\ModuleRepositoryContract;
use App\Services\V1\Course\CourseServiceContract;
use Illuminate\Support\Collection;

class ModuleService implements ModuleServiceContract
{
    /**
     * __construct
     *
     * @param CourseServiceContract $courseServices
     * @param ModuleRepositoryContract $moduleRepository
     */
    public function __construct(
        protected CourseServiceContract $courseService,
        protected ModuleRepositoryContract $moduleRepository,
    ) {
    }

    public function getModulesByCourse(string $course): Collection
    {
        $course = $this->courseService->getCourse($course);

        return $this->moduleRepository->getModulesByCourse($course->id);
    }

    public function store(array $data): Module
    {
        return $this->moduleRepository->store($data);
    }

    public function getModuleByCourse(string $courseUuid, string $moduleUuid): Module
    {
        $course = $this->courseService->getCourse($courseUuid);

        return $this->moduleRepository->getModuleByCourse($course->id, $moduleUuid);

    }

    public function updateModule(string $moduleUuid, array $data): bool
    {
        return $this->moduleRepository->updateModule($moduleUuid, $data);
    }

    public function destroyModule(string $moduleUuid): bool|null
    {
        return $this->moduleRepository->destroyModule($moduleUuid);
    }
}