<?php

namespace App\DataTables;

use App\Enums\RoleEnum;
use App\Models\Revenue;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RevenueDataTable extends DataTable
{
    private $resource;

    public function __construct()
    {
        $this->resource = Revenue::getTableName();
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
            ->addColumn('email', function ($revenue) {
                return $revenue->user->email;
            })
            ->addColumn('name', function ($revenue) {
                return $revenue->user->name;
            })
            ->addColumn('trainingPackage', function ($revenue) {
                return $revenue->trainingPackage->name;
            });

        if (auth()->user()->hasRole(RoleEnum::ADMIN)) {
            $dataTables->addColumn('gym', function ($revenue) {
                return $revenue->gym->name;
            })
                ->addColumn('city', function ($revenue) {
                    return $revenue->gym->city->name;
                });
        } else if (auth()->user()->hasRole(RoleEnum::City_MANAGER)) {
            $dataTables->addColumn('gym', function ($revenue) {
                return $revenue->gym->name;
            });
        }

        return $dataTables;


    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Revenue $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Revenue $model)
    {
        if (auth()->user()->hasRole(RoleEnum::ADMIN)) {
            $query = $model->newQuery()->with('user','trainingPackage','gym','gym.city');
        } else if (auth()->user()->hasRole(RoleEnum::GYM_MANAGER)) {
            $query = $model->newQuery()->with('user','trainingPackage','gym','gym.city')->where('gym_id', 1);
        } else if (auth()->user()->hasRole(RoleEnum::City_MANAGER)) {
            $query = $model->newQuery()->with('user','trainingPackage','gym','gym.city')->whereRelation('gym','city_id',1);
        }
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId($this->resource . '-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
//            ->parameters([
//                'dom' => 'Blfrtip',
//                'responsive' => true,
//                'autoWidth'  => true,
//                'buttons' => [
//                    [
//                        'text' => 'Create',
//                        'className' => 'button create-btn'
//                    ],
//                    'copy', 'excel', 'pdf', 'print'],
//
//            ]);
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
                'name' => 'email',
                'data' => 'email',
                'title' => 'User Email',
            ],
            [
                'name' => 'name',
                'data' => 'name',
                'title' => 'User Name',
            ],
            [
                'name' => 'amount',
                'data' => 'amount',
                'title' => 'Amount ($)',
            ],
            [
                'name' => 'trainingPackage',
                'data' => 'trainingPackage',
                'title' => 'Training Package Name',
            ],


        ];

        if (auth()->user()->hasRole(RoleEnum::ADMIN)) {
            $inserted1 [] = [
                'data' => 'gym',
                'name' => 'gym',
                'title' => 'Gym Name',
            ];
            array_splice($columns, 4, 0, $inserted1);

            $inserted2 [] = [
                'data' => 'city',
                'name' => 'city',
                'title' => 'City Name',
            ];
            array_splice($columns, 5, 0, $inserted2);
        }  elseif (auth()->user()->hasRole(RoleEnum::City_MANAGER)) {
            $inserted [] = [
                'data' => 'gym',
                'name' => 'gym',
                'title' => 'Gym Name',
            ];
            array_splice($columns, 4, 0, $inserted);
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
        return 'Revenue_' . date('YmdHis');
    }
}
