<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\UserRequest;
use App\Models\TrainingPackage;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use HasFiles;

    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN);
        $this->authorizeResource(User::class);

        $this->resource = User::getTableName();

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'name',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'date',
                'name' => 'date_of_birth',
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
                'element' => 'input',
                'type' => 'radio',
                'name' => 'gender',
                'required' => 'create|edit',
                'options'  => [
                    [
                        'value' => 'male'
                    ],
                    [
                        'value' => 'female'
                    ]
                ]
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
            'name', 'role_name', 'email', 'national_id','date_of_birth', 'gender', 'avatar'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $userDataTable)
    {

        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $userDataTable->render('index', get_defined_vars());
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
    public function store(UserRequest $request)
    {
        $reqData = $request->only([
            'name',
            'national_id',
            'date_of_birth',
            'email',
            'password',
        ]);

        $reqData['gender'] = last($request->input('gender'));

        $user = User::create($reqData);

        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            $path = $this->storeFile($this->resource, $avatar, $avatar->getClientOriginalName(), $user);
            $user->avatar()->create(['path' => $path]);
        } else {
            $user->avatar()->create(['path' => 'default-avatar.png']);
        }

        $user->assignRole(RoleEnum::USER);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;

        $resourceObject = $this->mapModelToCollection($user);

        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $this->loadModelRelations($user);
        $resourceObject = $this->mapModelToCollection($user);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {

        $reqData = $request->only([
            'name',
            'national_id',
            'date_of_birth',
            'email',
        ]);

        $reqData['gender'] = last($request->input('gender'));

        if ($request->password) {
            $reqData['password'] = Hash::make($request->password);
        }

        $user->update($reqData);

        if ($request->hasFile('avatar')) {

            $attachment = $user->avatar()->first();
            $attachment->delete();

            $avatar = $request->avatar;
            $path = $this->storeFile($this->resource, $avatar, $avatar->getClientOriginalName(), $user);
            $user->avatar()->create(['path' => $path]);
        }

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' updated successfully',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $attachment = $user->avatar()->first();
        $attachment->delete();
        $user->roles()->detach();
        $user->trainingSessions()->detach();
        $user->trainingPackages()->detach();

        $user->delete();

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
        $resourceCollection->date_of_birth = $model->date_of_birth;
        $resourceCollection->gender = $model->gender;
        $resourceCollection->email = $model->email;
        $resourceCollection->role = $model->roles->first();
        $resourceCollection->role_name = optional($model->roles->first())->name;
        $resourceCollection->avatar = optional($model->avatar)->attachment_url;

        return $resourceCollection;
    }
}
