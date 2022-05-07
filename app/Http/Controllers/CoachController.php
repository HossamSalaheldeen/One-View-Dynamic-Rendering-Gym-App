<?php

namespace App\Http\Controllers;

use App\DataTables\CoachDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\CoachRequest;
use App\Models\Coach;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CoachController extends Controller
{
    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::GYM_MANAGER);
        $this->authorizeResource(Coach::class);

        $this->resource = Coach::getTableName();

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'name',
                'required' => 'create|edit'
            ],
        ];

        $this->attributes = ['name'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CoachDataTable $coachDataTable)
    {
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $coachDataTable->render('index',get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCoachRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CoachRequest $request)
    {
        $reqData = $request->only([
            'name'
        ]);

        Coach::create($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' created successfully',
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function show(Coach $coach)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($coach);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function edit(Coach $coach)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $resourceObject = $this->mapModelToCollection($coach);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCoachRequest  $request
     * @param  \App\Models\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function update(CoachRequest $request, Coach $coach)
    {
        $reqData = $request->only([
            'name'
        ]);

        $coach->update($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' updated successfully',
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coach $coach)
    {
        $coach->trainingSessions()->detach();
        $coach->delete();

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)).' deleted successfully',
        ],Response::HTTP_OK);
    }

    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id         = $model->id;
        $resourceCollection->name       = $model->name;

        return $resourceCollection;
    }
}
