<?php

namespace App\Http\Controllers;

use App\DataTables\CityDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::USER);

        $this->authorizeResource(City::class);

        $this->resource = City::getTableName();

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
    public function index(CityDataTable $cityDataTable)
    {
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $cityDataTable->render('index',get_defined_vars());
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
     * @param  \App\Http\Requests\StoreCityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        $reqData = $request->only([
            'name'
        ]);

        City::create($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' created successfully',
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($city);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $resourceObject = $this->mapModelToCollection($city);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCityRequest  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, City $city)
    {
        $reqData = $request->only([
            'name'
        ]);

        $city->update($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' updated successfully',
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();

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
