<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\Attendance;
use App\Models\TrainingSession;
use App\Models\TrainingSessionUser;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttendanceDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Attendance::getTableName();
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $userRole = auth()->user()->roles->first()->name;

        switch ($userRole) {
            case RoleEnum::ADMIN:
                return datatables()->eloquent($query)
                    ->addColumn('user', function ($trainingSessionUser) {
                        return $trainingSessionUser->user->name;
                    })
                    ->addColumn('email', function ($trainingSessionUser) {
                        return $trainingSessionUser->user->email;
                    })
                    ->addColumn('trainingSession', function ($trainingSessionUser) {
                        return $trainingSessionUser->trainingSession->name;
                    })
                    ->addColumn('gym', function ($trainingSessionUser) {
                        return $trainingSessionUser->gym->name;
                    })
                    ->addColumn('city', function ($trainingSessionUser) {
                        return $trainingSessionUser->gym->city->name;
                    });
            case RoleEnum::City_MANAGER:
                return datatables()->eloquent($query)
                    ->addColumn('user', function ($trainingSessionUser) {
                        return $trainingSessionUser->user->name;
                    })
                    ->addColumn('email', function ($trainingSessionUser) {
                        return $trainingSessionUser->user->email;
                    })
                    ->addColumn('trainingSession', function ($trainingSessionUser) {
                        return $trainingSessionUser->trainingSession->name;
                    })
                    ->addColumn('gym', function ($trainingSessionUser) {
                        return $trainingSessionUser->gym->name;
                    });
            case RoleEnum::GYM_MANAGER:

                return datatables()->eloquent($query)
                    ->addColumn('user', function ($trainingSessionUser) {
                        return $trainingSessionUser->user->name;
                    })
                    ->addColumn('email', function ($trainingSessionUser) {
                        return $trainingSessionUser->user->email;
                    })
                    ->addColumn('trainingSession', function ($trainingSessionUser) {
                        return $trainingSessionUser->trainingSession->name;
                    });
            case RoleEnum::USER:
                return datatables()->eloquent($query)
                    ->addColumn('trainingSession', function ($trainingSessionUser) {
                        return $trainingSessionUser->trainingSession->name;
                    });
            default:
                return datatables()->eloquent($query);
        }

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Attendance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $userRole = auth()->user()->roles->first()->name;

        switch ($userRole) {
            case RoleEnum::ADMIN:
                return TrainingSessionUser::query();
            case RoleEnum::City_MANAGER:
                return TrainingSessionUser::query()->whereRelation('gym','city_id',auth()->user()->city_id)->attended(true);
            case RoleEnum::GYM_MANAGER:
                return TrainingSessionUser::query()->where('gym_id', auth()->user()->gym_id)->attended(true);
            case RoleEnum::USER:
                return TrainingSessionUser::query()->where('user_id', auth()->user()->id)->attended(true);
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
            case RoleEnum::City_MANAGER:
            case RoleEnum::GYM_MANAGER:
            return $this->builder()
                ->setTableId($this->resource . '-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->parameters([
                    'dom' => 'Blfrtip',
                    'responsive' => true,
                    'autoWidth'  => true,
                    'buttons' => ['copy', 'excel', 'pdf', 'print'],

                ]);
            case RoleEnum::USER:
                return $this->builder()
                    ->setTableId($this->resource . '-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax();
            default:
                return $this->builder()
                    ->setTableId($this->resource . '-table')
                    ->columns($this->getColumns());
        }

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $userRole = auth()->user()->roles->first()->name;

        switch ($userRole) {
            case RoleEnum::ADMIN:
                return [
                    [
                        'name' => 'user',
                        'data' => 'user',
                        'title' => 'User',
                    ],
                    [
                        'name' => 'email',
                        'data' => 'email',
                        'title' => 'Email',
                    ],
                    [
                        'name' => 'trainingSession',
                        'data' => 'trainingSession',
                        'title' => 'Training Session',
                    ],
                    [
                        'name' => 'time',
                        'data' => 'time',
                        'title' => 'Time',
                    ],
                    [
                        'name' => 'date',
                        'data' => 'date',
                        'title' => 'Date',
                    ],
                    [
                        'data' => 'gym',
                        'name' => 'gym',
                        'title' => 'Gym Name',
                    ],
                    [
                        'data' => 'city',
                        'name' => 'city',
                        'title' => 'City Name',
                    ]
                ];
            case RoleEnum::City_MANAGER:
                return [
                    [
                        'name' => 'user',
                        'data' => 'user',
                        'title' => 'User',
                    ],
                    [
                        'name' => 'email',
                        'data' => 'email',
                        'title' => 'Email',
                    ],
                    [
                        'name' => 'trainingSession',
                        'data' => 'trainingSession',
                        'title' => 'Training Session',
                    ],
                    [
                        'name' => 'time',
                        'data' => 'time',
                        'title' => 'Time',
                    ],
                    [
                        'name' => 'date',
                        'data' => 'date',
                        'title' => 'Date',
                    ],
                    [
                        'data' => 'gym',
                        'name' => 'gym',
                        'title' => 'Gym Name',
                    ],
                ];
            case RoleEnum::GYM_MANAGER:
                return [
                    [
                        'name' => 'user',
                        'data' => 'user',
                        'title' => 'User',
                    ],
                    [
                        'name' => 'email',
                        'data' => 'email',
                        'title' => 'Email',
                    ],
                    [
                        'name' => 'trainingSession',
                        'data' => 'trainingSession',
                        'title' => 'Training Session',
                    ],
                    [
                        'name' => 'time',
                        'data' => 'time',
                        'title' => 'Time',
                    ],
                    [
                        'name' => 'date',
                        'data' => 'date',
                        'title' => 'Date',
                    ],
                ];
            case RoleEnum::USER:
                return [
                    [
                        'name' => 'trainingSession',
                        'data' => 'trainingSession',
                        'title' => 'Training Session',
                    ],
                    [
                        'name' => 'time',
                        'data' => 'time',
                        'title' => 'Time',
                    ],
                    [
                        'name' => 'date',
                        'data' => 'date',
                        'title' => 'Date',
                    ],
                ];
            default:
                return [];
        }

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Attendance_' . date('YmdHis');
    }
}
