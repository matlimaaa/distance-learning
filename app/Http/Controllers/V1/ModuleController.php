<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUpdateModuleValidate;
use App\Http\Resources\V1\ModuleResource;
use App\Services\V1\Module\ModuleServiceContract;
use Illuminate\Http\Response;

class ModuleController extends Controller
{
    /**
     * __construct
     *
     * @param ModuleServiceContract $moduleService
     */
    public function __construct(
        protected ModuleServiceContract $moduleService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $course)
    {
        $modules = $this->moduleService->getModulesByCourse($course);

        return ModuleResource::collection($modules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateModuleValidate $request, string $courseUuid)
    {
        $module = $this->moduleService->store($request->validated(), $courseUuid);

        return new ModuleResource($module);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $courseUuid, string $moduleUuid)
    {
        $module = $this->moduleService->getModuleByCourse($courseUuid, $moduleUuid);

        return new ModuleResource($module);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateModuleValidate $request, string $courseUuid, string $moduleUuid)
    {
        $this->moduleService->updateModule($request->validated(), $courseUuid, $moduleUuid);

        return response()->json(['message' => 'updated'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $course, string $uuid)
    {
        $this->moduleService->destroyModule($uuid);
        
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
