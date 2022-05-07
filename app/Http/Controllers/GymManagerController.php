<?php

namespace App\Http\Controllers;

use App\DataTables\GymManagerDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\GymManagerRequest;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GymManagerController extends Controller
{
    use HasFiles;

    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER);
        $this->resource = Str::plural(RoleEnum::GYM_MANAGER);

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
                'name' => 'gym',
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
            'name', 'role_name', 'email', 'national_id','gym_name','avatar'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GymManagerDataTable $gymManagerDataTable)
    {

        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $gymManagerDataTable->render('index', get_defined_vars());
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
    public function store(GymManagerRequest $request)
    {
//        dd($request->all());
        $reqData = $request->only([
            'name',
            'national_id',
            'email',
            'password',
            'gym_id'
        ]);

        $gym_manager = User::create($reqData);

        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            $path = $this->storeFile($this->resource, $avatar, $avatar->getClientOriginalName(), $gym_manager);
            $gym_manager->avatar()->create(['path' => $path]);
        } else {
            $gym_manager->avatar()->create(['path' => 'default-avatar.png']);
        }

        $gym_manager->assignRole(RoleEnum::GYM_MANAGER);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $gym_manager
     * @return \Illuminate\Http\Response
     */
    public function show(User $gym_manager)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;

        $resourceObject = $this->mapModelToCollection($gym_manager);

        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $gym_manager
     * @return \Illuminate\Http\Response
     */
    public function edit(User $gym_manager)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $this->loadModelRelations($gym_manager);
        $resourceObject = $this->mapModelToCollection($gym_manager);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $gym_manager
     * @return \Illuminate\Http\Response
     */
    public function update(GymManagerRequest $request, User $gym_manager)
    {
        $reqData = $request->only([
            'name',
            'national_id',
            'email',
            'gym_id'
        ]);

        if ($request->password) {
            $reqData['password'] = Hash::make($request->password);
        }

        $gym_manager->update($reqData);

        if ($request->hasFile('avatar')) {

            $attachment = $gym_manager->avatar()->first();
            $attachment->delete();

            $avatar = $request->avatar;
            $path = $this->storeFile($this->resource, $avatar, $avatar->getClientOriginalName(), $gym_manager);
            $gym_manager->avatar()->create(['path' => $path]);
        }

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' updated successfully',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $gym_manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $gym_manager)
    {
        $attachment = $gym_manager->avatar()->first();
        $attachment->delete();
        $gym_manager->roles()->detach();
        $gym_manager->gym()->dissociate();

        $gym_manager->delete();

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' deleted successfully',
        ], Response::HTTP_OK);
    }

    public function toggleBan(User $gym_manager)
    {
        if ($gym_manager->isNotBanned()) {
            $gym_manager->ban();

            return response([
                'message' => Str::ucfirst(Str::singular($this->resource)) . ' baned successfully',
            ], Response::HTTP_OK);
        } else {
            $gym_manager->unban();

            return response([
                'message' => Str::ucfirst(Str::singular($this->resource)) . ' unbaned successfully',
            ], Response::HTTP_OK);
        }

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
        $resourceCollection->gym = $model->gym;
        $resourceCollection->gym_name = optional($model->gym)->name;
        $resourceCollection->avatar = optional($model->avatar)->attachment_url;

        return $resourceCollection;
    }
}
