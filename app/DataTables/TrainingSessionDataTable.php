<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\TrainingSession;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TrainingSessionDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Str::slug(TrainingSession::getTableName());
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
            ->addColumn('coaches_number', function ($trainingSession) {
                return $trainingSession->coaches_count;
            })
            ->addColumn('actions', function ($trainingSession) {
                $showRoles = [RoleEnum::ADMIN, RoleEnum::GYM_MANAGER, RoleEnum::USER];
                $editRoles = [RoleEnum::ADMIN, RoleEnum::GYM_MANAGER];
                $deleteRoles = [RoleEnum::ADMIN, RoleEnum::GYM_MANAGER];
                $attendRoles = [RoleEnum::USER];
                $resource = $this->resource;
                $resourceId = $trainingSession->id;
                return view('datatables.columns.actions', get_defined_vars());
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
     * @param \App\Models\TrainingSession $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TrainingSession $model)
    {
        $userRole = auth()->user()->roles->first()->name;

        switch ($userRole) {
            case RoleEnum::ADMIN:
            case RoleEnum::GYM_MANAGER:
                return $model->newQuery()->withCount('coaches');
            case RoleEnum::USER:
                return $model->newQuery()
                    ->whereHas('users', function ($q) {
                        $q->where('training_session_user.user_id', auth()->user()->id)
                            ->where('training_session_user.is_attended', false);
                    })->withCount('coaches');

            default:
                return $model->newQuery();
        }
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
                'data' => 'starts_at',
                'name' => 'starts_at',
                'title' => 'Starts At',
            ],
            [
                'data' => 'finishes_at',
                'name' => 'finishes_at',
                'title' => 'Finishes At',
            ],
            [
                'data' => 'coaches_number',
                'name' => 'coaches_number',
                'title' => 'Coaches Number',
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
        return 'TrainingSession_' . date('YmdHis');
    }
}
