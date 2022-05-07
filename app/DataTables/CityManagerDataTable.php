<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\CityManager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CityManagerDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Str::plural(RoleEnum::City_MANAGER);
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
                $showRoles = [RoleEnum::ADMIN];
                $editRoles = [RoleEnum::ADMIN];
                $deleteRoles = [RoleEnum::ADMIN];
                $resource = $this->resource;
                $resourceId = $user->id;
                return view('datatables.columns.actions',get_defined_vars());
            })
            ->addColumn('city', function ($user){
                return $user->city?->name;
            })
            ->addColumn('avatar', function ($user){
                $attachmentUrl = $user->avatar?->attachment_url;
                return view('datatables.columns.attachments',compact('attachmentUrl'));
            })->editColumn('created_at', function($data){
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
            })->rawColumns([
                'avatar',
                'actions',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->role(RoleEnum::City_MANAGER);
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
                'data' => 'name',
                'name' => 'name',
                'title' => 'Name',
            ],
            [
                'data'  => 'national_id',
                'name'  => 'national_id',
                'title' => 'National Id',
            ],
            [
                'data'  => 'email',
                'name'  => 'email',
                'title' => 'Email',
            ],
            [
                'data'  => 'city',
                'name'  => 'city.name',
                'title' => 'City',
            ],
            [
                'data'       => 'avatar',
                'name'       => 'avatar',
                'title'      => 'Avatar',
                'exportable' => false,
                'printable'  => false,
                'orderable'  => false,
                'searchable' => false,
            ],
            [
                'name'  => 'created_at',
                'data'  => 'created_at',
                'title' => 'Created At',
            ],
            [
                'data' => 'actions',
                'name' => 'actions',
                'title' => 'Actions',
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
        return 'CityManager_' . date('YmdHis');
    }
}
