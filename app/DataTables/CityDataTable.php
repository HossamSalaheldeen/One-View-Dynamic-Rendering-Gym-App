<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\City;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CityDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = City::getTableName();
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($user){
                $showRoles = [RoleEnum::ADMIN,RoleEnum::USER];
                $editRoles = [RoleEnum::ADMIN];
                $deleteRoles = [RoleEnum::ADMIN];
                $resource = $this->resource;
                $resourceId = $user->id;
                return view('datatables.columns.actions',get_defined_vars());
            })
            ->editColumn('created_at', function($data){
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
            })->rawColumns([
                'actions',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\City $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(City $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $userRole = auth()->user()->roles->first()->name;

        switch ($userRole) {
            case RoleEnum::ADMIN:
                return $this->builder()
                    ->setTableId($this->resource . '-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        'dom' => 'Blfrtip',
                        'responsive' => true,
                        'autoWidth' => true,
                        'buttons' => [
                            [
                                'text' => 'Create',
                                'className' => 'button create-btn'
                            ],
                            'copy', 'excel', 'pdf', 'print'],

                    ]);
            case RoleEnum::USER:
                return $this->builder()
                    ->setTableId($this->resource . '-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax();
            default:
                return $this->builder()
                    ->setTableId($this->resource . '-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
//                        'dom' => 'Blfrtip',
                        'responsive' => true,
                        'autoWidth' => true,
                        'buttons' => [
                            [
                                'text' => 'Create',
                                'className' => 'create-btn'
                            ],
                            'copy', 'excel', 'pdf', 'print'],

                    ]);
        }
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            [
                'name' => 'name',
                'data' => 'name',
                'title' => 'name',
            ],
            [
                'name'  => 'created_at',
                'data'  => 'created_at',
                'title' => 'Created At',
            ],
            [
                'name' => 'actions',
                'data' => 'actions',
                'title' => 'actions',
                'exportable' => false,
                'printable' => false,
                'orderable' => false,
                'searchable' => false,
            ],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'City_' . date('YmdHis');
    }
}
