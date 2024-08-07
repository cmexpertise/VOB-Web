<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\UserAnalytic;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserAnalyticsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $counter = 1;
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if ($this->request->has('search')) {
                    $keyword = $this->request->get('search');
                    $keyword = $keyword['value'];
                    $query->orWhere(function ($q) use ($keyword) {
                        $q->orWhereHas('payment', function ($q) use ($keyword) {
                            $q->orWhere('created_at', 'LIKE', "%{$keyword}%")
                                ->orWhere('package_name', 'LIKE', "%{$keyword}%")
                                ->orWhere('transaction_id', 'LIKE', "%{$keyword}%")
                                ->orWhere('amount', 'LIKE', "%{$keyword}%");
                        })
                        ->orWhere('email', 'LIKE', "%{$keyword}%")
                        ->orWhere('name', 'LIKE', "%{$keyword}%");
                    })->where('role_id',2);
                }
            })
            ->addColumn('sr_no', function () use (&$counter) {
                return $counter++;
            })
            ->addColumn('payment.created_at', function($row){
                return (isset($row->package) && $row->package != null ) ? date('d-m-Y H:i:s',strtotime($row->package->created_at)): null;
            })
            ->addColumn('payment.package_name', function($row){
                return (isset($row->package) && $row->package != null ) ? $row->package->package_name: null;
            })
            ->addColumn('payment.amount', function($row){
                return (isset($row->package) && $row->package != null ) ? $row->package->amount: null;
            })
            ->addColumn('payment.transaction_id', function($row){
                return (isset($row->package) && $row->package != null ) ? $row->package->transaction_id: null;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserAnalytic $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return User::with(['payment' => function ($query) {
            $query->select('id','user_id','transaction_id','package_name','amount','created_at');
        }])
            ->where('role_id',2)
            ->select('id','name','email');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('useranalytics-table')
                    ->setTableAttributes(['class'=>'table datatable pt-4 pb-4'])
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('sr_no')->title('#'),
            Column::make('name')->title('User Name'),
            Column::make('email')->title('Email'),
            Column::make('payment.created_at')->title('Payment Date'),
            Column::make('payment.package_name')->title('Package'),
            Column::make('payment.amount')->title('Amount'),
            Column::make('payment.transaction_id')->title('Transaction ID'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'UserAnalytics_' . date('YmdHis');
    }
}
