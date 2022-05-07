<?php

namespace App\Http\Controllers;

use App\DataTables\AttendanceDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\City;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER.'|'.RoleEnum::GYM_MANAGER.'|'.RoleEnum::USER);
        $this->resource = Attendance::getTableName();

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'time',
                'required' => 'create|edit'
            ],
            [
                'element' => 'input',
                'type' => 'text',
                'name' => 'date',
                'required' => 'create|edit'
            ],
        ];

        $this->attributes = ['time','date'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AttendanceDataTable $attendanceDataTable)
    {
//        $trainingSessions = auth()->user()->attendedTrainingSessions()->get();
//        dd($trainingSessions);
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;
        return $attendanceDataTable->render('index',get_defined_vars());
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
     * @param  \App\Http\Requests\StoreAttendanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceRequest $request)
    {
        $reqData = $request->only([
            'time',
            'date'
        ]);

        Attendance::create($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' created successfully',
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($attendance);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $resourceObject = $this->mapModelToCollection($attendance);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceRequest  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(AttendanceRequest $request, Attendance $attendance)
    {
        $reqData = $request->only([
            'time',
            'date'
        ]);

        $attendance->update($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' updated successfully',
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)).' deleted successfully',
        ],Response::HTTP_OK);
    }

    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id         = $model->id;
        $resourceCollection->time       = $model->time;
        $resourceCollection->date       = $model->date;

        return $resourceCollection;
    }
}
