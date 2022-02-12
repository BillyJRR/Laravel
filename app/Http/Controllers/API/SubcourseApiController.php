<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Subcourse\SubcourseStoreRequest;
use App\Http\Requests\Subcourse\SubcourseUpdateRequest;
use App\Http\Resources\SubcourseResource;
use App\Models\Subcourse;

class SubcourseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubcourseResource::collection(Subcourse::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcourseStoreRequest $request)
    {
        return (new SubcourseResource(Subcourse::create($request->all())))
                ->additional(['msg' => "Sub-Curso creado correctamente"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subcourse $subcourse_api)
    {
        return new SubcourseResource($subcourse_api);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubcourseUpdateRequest $request, Subcourse $subcourse_api)
    {
        $subcourse_api->update($request->all());

        return (new SubcourseResource($subcourse_api))
                ->additional(["msg" => "Sub-Curso actualizado correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcourse $subcourse_api)
    {
        $subcourse_api->delete();

        return (new SubcourseResource($subcourse_api))
                ->additional(["msg" => "Sub-Curso eliminado correctamente"]);
    }
}
