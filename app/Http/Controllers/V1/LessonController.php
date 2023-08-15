<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUpdateLessonValidate;
use App\Http\Resources\V1\LessonResource;
use App\Services\V1\Lesson\LessonServiceContract;
use Illuminate\Http\Response;

class LessonController extends Controller
{
    /**
     * __construct
     *
     * @param LessonServiceContract $lessonService
     */
    public function __construct(
        protected LessonServiceContract $lessonService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $moduleUuid)
    {
        $lessons = $this->lessonService->getLessonsByModule($moduleUuid);

        return LessonResource::collection($lessons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateLessonValidate $request, string $moduleUuid)
    {
        $module = $this->lessonService->store($request->validated(), $moduleUuid);

        return new LessonResource($module);
    }

    /**
     * Display the specified resource.moduleUuid
     */
    public function show(string $moduleUuid, string $lessonUuid)
    {
        $lesson = $this->lessonService->getLessonByModule($lessonUuid, $moduleUuid);

        return new LessonResource($lesson);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateLessonValidate $request, string $moduleUuid, string $lessonUuid)
    {
        $this->lessonService->updateLesson($request->validated(), $lessonUuid, $moduleUuid);

        return response()->json(['message' => 'updated'], Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $module, string $uuid)
    {
        $this->lessonService->destroyLesson($uuid);
        
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
