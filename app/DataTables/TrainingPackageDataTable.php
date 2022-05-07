<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\TrainingPackage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TrainingPackageDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Str::slug(TrainingPackage::getTableName());
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTables = datatables()
            ->eloquent($query)
            ->addColumn('sessions_number', function ($trainingPackage) {
                return $trainingPackage->training_sessions_count;
            })
            ->addColumn('actions', function ($trainingPackage) {
                $showRoles = [RoleEnum::ADMIN,RoleEnum::City_MANAGER,RoleEnum::GYM_MANAGER,RoleEnum::USER];
                $editRoles = [RoleEnum::ADMIN,RoleEnum::GYM_MANAGER];
                $deleteRoles = [RoleEnum::ADMIN,RoleEnum::GYM_MANAGER];
                $resource = $this->resource;
                $resourceId = $trainingPackage->id;
                return view('datatables.columns.actions', get_defined_vars());
            })
            ->editColumn('price', function ($trainingPackage) {
                return $trainingPackage->dollar_price;
            })
            ->editColumn('created_at', function ($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
            })
            ->rawColumns([
                'actions',
            ]);

        return $dataTables;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TrainingPackage $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TrainingPackage $model)
    {
        return $model->newQuery()->withCount('trainingSessions');
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
            case RoleEnum::City_MANAGER:
            case RoleEnum::GYM_MANAGER:
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
        $columns = [
            [
                'name' => 'name',
                'data' => 'name',
                'title' => 'name',
            ],
            [
                'data' => 'price',
                'name' => 'price',
                'title' => 'Price($)',
            ],
            [
                'data' => 'sessions_number',
                'name' => 'sessions_number',
                'title' => 'Sessions Number',
            ],
            [
                'name' => 'created_at',
                'data' => 'created_at',
                'title' => 'Created At',
            ],
            [
                'name' => 'actions',
                'data' => 'actions',
                'title' => 'Actions',
                'exportable' => false,
                'printable' => false,
                'orderable' => false,
                'searchable' => false,
            ],

        ];
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'TrainingPackage_' . date('YmdHis');
    }
}
