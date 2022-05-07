<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\Coach;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CoachDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Coach::getTableName();
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
                $showRoles = [RoleEnum::ADMIN, RoleEnum::GYM_MANAGER];
                $editRoles = [RoleEnum::ADMIN, RoleEnum::GYM_MANAGER];
                $deleteRoles = [RoleEnum::ADMIN, RoleEnum::GYM_MANAGER];
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
     * @param \App\Models\Coach $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Coach $model)
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
        return $this->builder()
            ->setTableId($this->resource.'-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom' => 'Blfrtip',
                'responsive' => true,
                'autoWidth'  => true,
                'buttons' => [
                    [
                        'text' => 'Create',
                        'className' => 'button create-btn'
                    ],
                    'copy', 'excel', 'pdf', 'print'],

            ]);
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
        return 'Coach_' . date('YmdHis');
    }
}
