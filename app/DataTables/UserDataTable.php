<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = User::getTableName();
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
            ->addColumn('actions', function ($user) {
                $showRoles = [RoleEnum::ADMIN];
                $editRoles = [RoleEnum::ADMIN];
                $deleteRoles = [RoleEnum::ADMIN];
                $resource = $this->resource;
                $resourceId = $user->id;
                return view('datatables.columns.actions', get_defined_vars());
            })
            ->addColumn('avatar', function ($user) {
                $attachmentUrl = $user->avatar ?->attachment_url;
                return view('datatables.columns.attachments', compact('attachmentUrl'));
            })
            ->editColumn('created_at', function ($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
            })
            ->rawColumns([
                'avatar',
                'actions',
            ]);
//            ->addColumn('roles', function (User $user) {
//                return $user->roles->first()->name;
//            })

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
//        $userRole = auth()->user()->roles->first()->name;
//        switch ($userRole) {
//            case RoleEnum::ADMIN:
//                echo "Your favorite color is red!";
//                break;
//            case RoleEnum::City_MANAGER:
//                echo "Your favorite color is blue!";
//                break;
//            case RoleEnum::GYM_MANAGER:
//                echo "Your favorite color is green!";
//                break;
//            default:
//                echo "Your favorite color is neither red, blue, nor green!";
//        }
        return $model->newQuery()->with('avatar')->role(RoleEnum::USER);
//        return User::query()->with(['avatar','roles'])->select('users.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId($this->resource. '-table')
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
                'data' => 'gender',
                'name' => 'gender',
                'title' => 'Gender',
            ],
            [
                'data' => 'date_of_birth',
                'name' => 'date_of_birth',
                'title' => 'Date Of Birth',
            ],
            [
                'data' => 'email',
                'name' => 'email',
                'title' => 'Email',
            ],
            [
                'data' => 'avatar',
                'name' => 'avatar',
                'title' => 'Avatar',
                'exportable' => false,
                'printable' => false,
                'orderable' => false,
                'searchable' => false,
            ],
//            [
//                'data'  => 'roles',
//                'name'  => 'roles.name',
//                'title' => 'Role',
//            ],
            [
                'name' => 'created_at',
                'data' => 'created_at',
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
        return 'User_' . date('YmdHis');
    }
}
