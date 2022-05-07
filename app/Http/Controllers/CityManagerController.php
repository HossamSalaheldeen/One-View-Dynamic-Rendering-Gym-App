<?php

namespace App\Http\Controllers;

use App\DataTables\CityManagerDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\CityManagerRequest;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CityManagerController extends Controller
{
    use HasFiles;

    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN);

        $this->resource = Str::plural(RoleEnum::City_MANAGER);

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'name',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'national_id',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'email',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'password',
                'name' => 'password',
                'required' => 'create'
            ],
            [
                'element' => 'input',
                'type' => 'password',
                'name' => 'password_confirmation',
                'required' => 'create'
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
                'name' => 'avatar',
                'required' => '',
                'default' => 'images/default-avatar.png'
            ],
        ];

        $this->attributes = [
            'name', 'role_name', 'email', 'national_id', 'city_name', 'avatar'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CityManagerDataTable $cityManagerDataTable)
    {

        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $cityManagerDataTable->render('index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityManagerRequest $request)
    {
//        dd($request->all());
        $reqData = $request->only([
            'name',
            'national_id',
            'email',
            'password',
            'city_id'
        ]);

        $city_manager = User::create($reqData);

        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            $path = $this->storeFile($this->resource, $avatar, $avatar->getClientOriginalName(), $city_manager);
            $city_manager->avatar()->create(['path' => $path]);
        } else {
            $city_manager->avatar()->create(['path' => 'default-avatar.png']);
        }

        $city_manager->assignRole(RoleEnum::City_MANAGER);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $city_manager
     * @return \Illuminate\Http\Response
     */
    public function show(User $city_manager)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;

        $resourceObject = $this->mapModelToCollection($city_manager);

        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $city_manager
     * @return \Illuminate\Http\Response
     */
    public function edit(User $city_manager)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $this->loadModelRelations($city_manager);
        $resourceObject = $this->mapModelToCollection($city_manager);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $city_manager
     * @return \Illuminate\Http\Response
     */
    public function update(CityManagerRequest $request, User $city_manager)
    {
        $reqData = $request->only([
            'name',
            'national_id',
            'email',
            'city_id'
        ]);

        if ($request->password) {
            $reqData['password'] = Hash::make($request->password);
        }

        $city_manager->update($reqData);

        if ($request->hasFile('avatar')) {

            $attachment = $city_manager->avatar()->first();
            $attachment->delete();

            $avatar = $request->avatar;
            $path = $this->storeFile($this->resource, $avatar, $avatar->getClientOriginalName(), $city_manager);
            $city_manager->avatar()->create(['path' => $path]);
        }

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' updated successfully',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $city_manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $city_manager)
    {
        $attachment = $city_manager->avatar()->first();
        $attachment->delete();
        $city_manager->roles()->detach();
        $city_manager->city()->dissociate();


        $city_manager->delete();

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' deleted successfully',
        ], Response::HTTP_OK);
    }

    private function loadModelRelations($model)
    {
        $model->load('avatar');
    }


    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id = $model->id;
        $resourceCollection->name = $model->name;
        $resourceCollection->national_id = $model->national_id;
        $resourceCollection->email = $model->email;
        $resourceCollection->role = $model->roles->first();
        $resourceCollection->role_name = optional($model->roles->first())->name;
        $resourceCollection->city = $model->city;
        $resourceCollection->city_name = optional($model->city)->name;
        $resourceCollection->avatar = optional($model->avatar)->attachment_url;

        return $resourceCollection;
    }
}
