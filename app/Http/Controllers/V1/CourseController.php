<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUpdateCourseValidate;
use App\Http\Resources\V1\CourseResource;
use App\Services\V1\Course\CourseServiceContract;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    /**
     * __construct
     *
     * @param CourseServiceContract $courseService
     */
    public function __construct(
        protected CourseServiceContract $courseService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = $this->courseService->getCourses();

        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCourseValidate $request)
    {
        $course = $this->courseService->store($request->validated());

        return new CourseResource($course);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $course = $this->courseService->getCourse($uuid);

        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCourseValidate $request, string $uuid)
    {
        $course = $this->courseService->update($uuid, $request->validated());

        return response()->json(['message' => 'updated'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $course = $this->courseService->destroy($uuid);
        
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
