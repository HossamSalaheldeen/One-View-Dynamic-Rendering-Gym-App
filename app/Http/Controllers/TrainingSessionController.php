<?php

namespace App\Http\Controllers;

use App\DataTables\TrainingSessionDataTable;
use App\Enums\RoleEnum;
use App\Models\TrainingSession;
use App\Http\Requests\TrainingSessionRequest;
use App\Models\TrainingSessionUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrainingSessionController extends Controller
{
    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::GYM_MANAGER.'|'.RoleEnum::USER);
        $this->authorizeResource(TrainingSession::class);


        $this->resource = Str::slug(TrainingSession::getTableName());

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
                'name' => 'starts_at',
                'required' => 'create|edit',
                'authorization' => ['users']
            ],
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'finishes_at',
                'required' => 'create|edit',
                'authorization' => ['users']
            ],
            [
                'element' => 'select',
                'type' => 'multiple',
                'name' => 'coach',
                'required' => 'create|edit'
            ],
        ];

        $this->attributes = ['name', 'starts_at', 'finishes_at', 'coachNames'];
    }

    protected function resourceAbilityMap()
    {
        $abilities = parent::resourceAbilityMap();
        $abilities['edit'] = 'edit';
        return array_merge(
            $abilities,
            [
                'change' => 'change',
                'attend' => 'attend'
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TrainingSessionDataTable $trainingSessionDataTable)
    {
//        $notAttendedSessions = TrainingSession::query()
//        ->whereHas('users' , function ($q){
//            $q
//                ->where('training_session_user.user_id',auth()->user()->id)
//              ->whereNull('training_session_user.time');
//        })
//            ->get();
//        dd($notAttendedSessions);
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $trainingSessionDataTable->render('index', get_defined_vars());
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
     * @param \App\Http\Requests\TrainingSessionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingSessionRequest $request)
    {
        $reqData = $request->only([
            'name',
            'starts_at',
            'finishes_at'
        ]);

        $trainingSession = TrainingSession::create($reqData);

        $trainingSession->coaches()->attach($request->coaches);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\TrainingSession $training_session
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingSession $training_session)
    {

        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($training_session);
//        dd($resourceObject->coachNames);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TrainingSession $training_session
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingSession $training_session)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $resourceObject = $this->mapModelToCollection($training_session);
        $resourceModel = $training_session;
//        dd($resourceObject);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateTrainingSessionRequest $request
     * @param \App\Models\TrainingSession $training_session
     * @return \Illuminate\Http\Response
     */
    public function update(TrainingSessionRequest $request, TrainingSession $training_session)
    {
        $reqData = $request->only([
            'name',
            'starts_at',
            'finishes_at'
        ]);


        $training_session->update($reqData);

        $training_session->coaches()->sync($request->coaches);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' updated successfully',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TrainingSession $training_session
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingSession $training_session)
    {
        $training_session->coaches()->detach();
        $training_session->delete();

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' deleted successfully',
        ], Response::HTTP_OK);
    }

    public function attend(TrainingSession $training_session)
    {
        TrainingSessionUser::query()
            ->where('training_session_id', $training_session->id)
            ->where('user_id', auth()->id())
            ->attended(false)
            ->update(['time' => Carbon::now()->toTimeString(), 'date' => Carbon::now()->toDateString(), 'is_attended' => true]);
//        auth()->user()->trainingSessions()->updateExistingPivot($training_session->id, [
//            'time' => Carbon::now()->toTimeString(),
//            'date' => Carbon::now()->toDateString(),
//            'is_attended' => true,
//        ]);

        return response([
            'message' => Str::ucfirst(Str::singular($this->resource)) . ' attended successfully',
        ], Response::HTTP_OK);
    }

    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id = $model->id;
        $resourceCollection->name = $model->name;
        $resourceCollection->starts_at = $model->starts_at;
        $resourceCollection->finishes_at = $model->finishes_at;
        $resourceCollection->coaches = $model->coaches;
        $resourceCollection->coachNames = $model->coaches->map(function ($coach) {
            return $coach->name;
        });

        return $resourceCollection;
    }

}
