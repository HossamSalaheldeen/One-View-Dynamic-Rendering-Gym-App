<?php

namespace App\Http\Controllers;

use App\DataTables\RevenueDataTable;
use App\Enums\RoleEnum;
use App\Http\Requests\RevenueRequest;
use App\Models\Revenue;
use Symfony\Component\HttpFoundation\Response;

class RevenueController extends Controller
{
    private $resource;

    private $fields;

    private $attributes;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER.'|'.RoleEnum::GYM_MANAGER);
        $this->resource = Revenue::getTableName();

        $this->fields = [
            [
                'element' => 'input',
                'type' => 'number',
                'name' => 'amount',
                'required' => 'create|edit'
            ],
        ];

        $this->attributes = ['amount'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RevenueDataTable $revenueDataTable)
    {
        $isEdit = false;
        $resource = $this->resource;
        $fields = $this->fields;

        $totalRevenue =Revenue::query()->sum('amount');
        $showTotalRevenueRole = [RoleEnum::ADMIN];
        $totalGymRevenue =Revenue::query()->where('gym_id', 1)->sum('amount');
        $showGymRevenueRole = [RoleEnum::ADMIN,RoleEnum::GYM_MANAGER];
        $totalCityRevenue =Revenue::query()->with('gym')->whereRelation('gym','city_id',1)->sum('amount');
        $showCityRevenueRole = [RoleEnum::ADMIN,RoleEnum::City_MANAGER];

        return $revenueDataTable->render('index',get_defined_vars());
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
     * @param  \App\Http\Requests\StoreRevenueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RevenueRequest $request)
    {
        $reqData = $request->only([
            'amount'
        ]);

        Revenue::create($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' created successfully',
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function show(Revenue $revenue)
    {
        $resource = $this->resource;
        $attributes = $this->attributes;
        $resourceObject = $this->mapModelToCollection($revenue);
        return view('modals.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function edit(Revenue $revenue)
    {
        $isEdit = true;
        $resource = $this->resource;
        $fields = $this->fields;
        $resourceObject = $this->mapModelToCollection($revenue);
        return view('modals.form', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRevenueRequest  $request
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function update(RevenueRequest $request, Revenue $revenue)
    {
        $reqData = $request->only([
            'amount'
        ]);

        $revenue->update($reqData);

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)) .' updated successfully',
        ],Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();

        return response([
            'message'  => Str::ucfirst(Str::singular($this->resource)).' deleted successfully',
        ],Response::HTTP_OK);
    }

    private function mapModelToCollection($model)
    {

        $resourceCollection = collect();
        $resourceCollection->id         = $model->id;
        $resourceCollection->amount       = $model->amount;

        return $resourceCollection;
    }
}
