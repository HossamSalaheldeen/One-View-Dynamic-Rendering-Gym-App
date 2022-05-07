<?php

namespace App\Http\Controllers;

use App\DataTables\GymDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\GymRequest;
use App\Models\Gym;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\Finder\name;

class GymController extends Controller
{
    use HasFiles;

    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER.'|'.RoleEnum::USER);
        $this->authorizeResource(Gym::class);

        $this->resource = Gym::getTableName();

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'name',
                'required' => 'create|edit'
            ],
            [
                'element' => 'select',
                'type' => 'single',
                'name' => 'city',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'file',
                'name' => 'cover',
                'required' => '',
                'default' => ''
            ],
        ];

        $this->attributes = ['name','city_name', 'cover'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GymDataTable $gymDataTable)
    {
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $gymDataTable->render('index',get_defined_vars());
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
     * @param  \App\Http\Requests\StoreGymRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GymRequest $request)
    {
        $reqData = $request->only([
            'name',
            'city_id'
        ]);

        $gym = Gym::create($reqData);

        if ($request->hasFile('cover')) {
            $cover = $request->cover;
            $path = $this->storeFile($this->resource, $cover, $cover->getClientOriginalName(), $gym);
            $gym->cover()->create(['path' => $path]);
        } else {
            $gym->cover()->create(['path' => 'default-cover.png']);
        }

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' created successfully',
        ],Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function show(Gym $gym)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($gym);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function edit(Gym $gym)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $this->loadModelRelations($gym);
        $resourceObject = $this->mapModelToCollection($gym);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGymRequest  $request
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function update(GymRequest $request, Gym $gym)
    {
        $gymData = $request->only([
            'name',
            'city_id'
        ]);

        if ($request->hasFile('cover')) {

            $attachment = $gym->cover()->first();
            $attachment->delete();

            $cover = $request->cover;
            $path = $this->storeFile($this->resource, $cover, $cover->getClientOriginalName(), $gym);
            $gym->cover()->create(['path' => $path]);
        }

        $gym->update($gymData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) . ' updated successfully',
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gym $gym)
    {
        $attachment = $gym->cover()->first();
        $attachment->delete();

        $gym->city()->dissociate();

        $gym->delete();

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) . ' deleted successfully',
        ],Response::HTTP_OK);
    }

    private function loadModelRelations($model)
    {
        $model->load('cover');
    }

    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id        = $model->id;
        $resourceCollection->name      = $model->name;
        $resourceCollection->city      = $model->city;
        $resourceCollection->city_name = optional($model->city)->name;
        $resourceCollection->cover = optional($model->cover)->attachment_url;
        return $resourceCollection;
    }
}
