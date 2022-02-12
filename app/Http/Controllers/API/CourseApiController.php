<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Course\CourseStoreRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Subcourse;

class CourseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CourseResource::collection(Course::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseStoreRequest $request)
    {
        return (new CourseResource(Course::create($request->all())))
                ->additional(['msg' => "Curso creado correctamente"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course_api)
    {
        return new CourseResource($course_api);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, Course $course_api)
    {
        $course_api->update($request->all());

        return (new CourseResource($course_api))
                ->additional(["msg" => "Curso actualizado correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course_api)
    {
        foreach ($course_api->subcourses as $subcourses) {
            $subcourses->delete();
        }
        $course_api->delete();

        return (new CourseResource($course_api))
                ->additional(["msg" => "Curso eliminado correctamente"]);
    }
}
