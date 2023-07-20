<?php

namespace App\Repositories\V1\Module;

use App\Models\Module;
use Illuminate\Database\Eloquent\Collection;

class ModuleRepository implements ModuleRepositoryContract
{
    /**
     * __construct
     *
     * @param Module $module
     */
    public function __construct(
        protected Module $module,
    ) {
    }

    public function getModulesByCourse(int $courseId): Collection
    {
        return $this->module->where('course_id', $courseId)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function store(int $courseId, array $data): Module
    {
        $data['course_id'] = $courseId;

        return $this->module->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleByCourse(int $courseId, string $moduleUuid): Module
    {
        return $this->module->where([
            'course_id', $courseId,
            'uuid' => $moduleUuid
        ])->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleByUuid(string $moduleUuid): Module
    {
        return $this->module->where('uuid', $moduleUuid)->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function updateModule(int $courseId, string $moduleUuid, array $data): bool
    {
        $data['course_id'] = $courseId;

        $module = $this->getModuleByUuid($moduleUuid);

        return $module->update($data);
    }

    /**
     * {@inheritDoc}
     */
    public function destroyModule(string $moduleUuid): bool|null
    {
        $module = $this->getModuleByUuid($moduleUuid);

        return $module->delete();
    }
}