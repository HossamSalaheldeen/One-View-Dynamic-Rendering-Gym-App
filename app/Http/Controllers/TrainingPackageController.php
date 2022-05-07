<?php

namespace App\Http\Controllers;

use App\DataTables\TrainingPackageDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\TrainingPackageRequest;
use App\Models\TrainingPackage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrainingPackageController extends Controller
{
    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER.'|'.RoleEnum::GYM_MANAGER.'|'.RoleEnum::USER);
        $this->authorizeResource(TrainingPackage::class);
        $this->resource = Str::slug(TrainingPackage::getTableName());

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'name',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'number',
                'name' => 'price',
                'required' => 'create|edit'
            ],
            [
                'element' => 'select',
                'type' => 'multiple',
                'name' => 'trainingSession',
                'required' => 'create|edit'
            ],
        ];

        $this->attributes = ['name', 'price','trainingSessionNames'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TrainingPackageDataTable $trainingPackageDataTable)
    {
//        $packages =TrainingPackage::withCount('trainingSessions')->get();
//        dd($packages);
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $trainingPackageDataTable->render('index', get_defined_vars());
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
     * @param \App\Http\Requests\StoreTrainingPackageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingPackageRequest $request)
    {
        $reqData = $request->only([
            'name',
            'price',
        ]);

        $trainingPackage = TrainingPackage::create($reqData);

        $trainingPackage->trainingSessions()->attach($request->trainingSessions);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\TrainingPackage $trainingPackage
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingPackage $trainingPackage)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($trainingPackage);
//        dd($resourceObject);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TrainingPackage $trainingPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingPackage $trainingPackage)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $resourceObject = $this->mapModelToCollection($trainingPackage);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateTrainingPackageRequest $request
     * @param \App\Models\TrainingPackage $trainingPackage
     * @return \Illuminate\Http\Response
     */
    public function update(TrainingPackageRequest $request, TrainingPackage $trainingPackage)
    {
        $reqData = $request->only([
            'name',
            'price',
        ]);

        $trainingPackage->update($reqData);

        $trainingPackage->trainingSessions()->sync($request->trainingSessions);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' updated successfully',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TrainingPackage $trainingPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingPackage $trainingPackage)
    {
        $trainingPackage->trainingSessions()->detach();
        $trainingPackage->gyms()->detach();

        $trainingPackage->delete();

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' deleted successfully',
        ], Response::HTTP_OK);
    }

    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id = $model->id;
        $resourceCollection->name = $model->name;
        $resourceCollection->price = $model->dollar_price;
        $resourceCollection->trainingSessions = $model->trainingSessions;
        $resourceCollection->trainingSessionNames = $model->trainingSessions->map(function ($trainingSession){
            return $trainingSession->name;
        });

        return $resourceCollection;
    }
}
