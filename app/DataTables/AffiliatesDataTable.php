<?php

namespace App\DataTables;

use App\Models\Affiliate;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AffiliatesDataTable extends DataTable
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
            ->addColumn('sr_no', function () use (&$counter) {
                return $counter++;
            })
            ->addColumn('action', function($row){
                $action = '<a href="#" class="btn btn-primary edit" data-id="'.$row->id.'" >
                <i class="ri-edit-box-fill"></i>
                    </a>
                    <button type="button" class="btn btn-danger deleteAffilate" data-id="'.encrypt($row->id).'" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="ri-delete-bin-5-fill"></i>
                    </button>';
                return $action;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Affiliate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Affiliate $model)
    {
        return Affiliate::select('id','name','email','mobile','coupon_code','affilate_url','percentage')
            ->orderBy('id','DESC');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('affiliates-table')
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
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('mobile')->title('Mobile'),
            Column::make('coupon_code')->title('Coupon Code'),
            Column::make('affilate_url')->title('Coupon Code'),
            Column::make('percentage')->title('Coupon Code'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-left'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'Affiliates_' . date('YmdHis');
    }
}
