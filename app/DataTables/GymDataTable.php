<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\Gym;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GymDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Gym::getTableName();
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
            ->addColumn('actions', function ($gym) {
                $showRoles = [RoleEnum::ADMIN, RoleEnum::City_MANAGER,RoleEnum::USER];
                $editRoles = [RoleEnum::ADMIN, RoleEnum::City_MANAGER];
                $deleteRoles = [RoleEnum::ADMIN, RoleEnum::City_MANAGER];
                $resource = $this->resource;
                $resourceId = $gym->id;
                return view('datatables.columns.actions', get_defined_vars());
            })
            ->addColumn('city', function ($gym) {
                return $gym->city ?->name;
            })
            ->addColumn('cover', function ($user) {
                $attachmentUrl = $user->cover ?->attachment_url;
                return view('datatables.columns.attachments', compact('attachmentUrl'));
            })
            ->editColumn('created_at', function ($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
            })
            ->rawColumns([
                'actions',
            ]);

        if (auth()->user()->hasRole(RoleEnum::ADMIN))
        {
            $dataTables->addColumn('createdBy', function ($gym) {
                return $gym->createdBy ?->name;
            });
        }

        return $dataTables;

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Gym $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Gym $model)
    {
        return $model->newQuery()->with('city','createdBy');
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
                'data' => 'cover',
                'name' => 'cover',
                'title' => 'Cover',
                'exportable' => false,
                'printable' => false,
                'orderable' => false,
                'searchable' => false,
            ],
            [
                'data' => 'city',
                'name' => 'city.name',
                'title' => 'City',
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

        if (auth()->user()->hasRole(RoleEnum::ADMIN)) {
            $inserted [] = [
                'data' => 'createdBy',
                'name' => 'createdBy.name',
                'title' => 'City Manager Name',
            ];

            array_splice( $columns, 2, 0, $inserted );
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Gym_' . date('YmdHis');
    }
}
