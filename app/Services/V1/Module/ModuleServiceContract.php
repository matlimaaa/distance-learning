<?php

namespace App\Services\V1\Module;

use App\Models\Module;
use Illuminate\Support\Collection;

interface ModuleServiceContract
{
    /**
     * getModulesByCourse
     *
     * @param string $course
     * @return Collection
     */
    public function getModulesByCourse(string $course): Collection;

    /**
     * store
     *
     * @param array $data
     * @return Module
     */
    public function store(array $data): Module;

    /**
     * getModuleByCourse
     *
     * @param string $courseUuid
     * @param string $moduleUuid
     * @return Module
     */
    public function getModuleByCourse(string $courseUuid, string $moduleUuid): Module;

    /**
     * updateModule
     *
     * @param string $moduleUuid
     * @param array $data
     * @return bool
     */
    public function updateModule(string $moduleUuid, array $data): bool;

    /**
     * destroyModule
     *
     * @param string $moduleUuid
     * @return bool|null
     */
    public function destroyModule(string $moduleUuid): bool|null;
}