<?php

namespace App\Repositories\V1\Module;

use App\Models\Module;
use Illuminate\Support\Collection;

interface ModuleRepositoryContract
{
    /**
     * getModulesByCourse
     *
     * @param int $courseId
     * @return Collection
     */
    public function getModulesByCourse(int $courseId): Collection;

    /**
     * store
     *
     * @param int $courseId
     * @param array $data
     * @return Module
     */
    public function store(int $courseId, array $data): Module;

    /**
     * getModuleByCourse
     *
     * @param int $courseId
     * @param string $moduleUuid
     * @return Module
     */
    public function getModuleByCourse(int $courseId, string $moduleUuid): Module;

    /**
     * getModuleByUuid
     *
     * @param string $moduleUuid
     * @return Module
     */
    public function getModuleByUuid(string $moduleUuid): Module;

    /**
     * updateModule
     *
     * @param int $courseId
     * @param string $moduleUuid
     * @param array $data
     * @return bool
     */
    public function updateModule(int $courseId, string $moduleUuid, array $data): bool;

    /**
     * destroyModule
     *
     * @param string $moduleUuid
     * @return bool|null
     */
    public function destroyModule(string $moduleUuid): bool|null;
}