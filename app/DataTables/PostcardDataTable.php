<?php

namespace App\DataTables;

use App\Models\Postcard;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PostcardDataTable extends DataTable
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
            ->addColumn('image', function ($row) {
                return '<img data-src="'.asset("storage/".$row->image).'" class="table-audio-img lazyload">';
            })
            ->addColumn('action', function($row){
                
                    $action = '<a href="#" class="btn btn-primary edit" data-id="'.$row->id.'" >
                                <i class="ri-edit-box-fill"></i>
                            </a>
                        <button type="button" class="btn btn-danger deletePostcard" data-id="'.encrypt($row->id).'" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="ri-delete-bin-5-fill"></i>
                    </button>';
                            
                
                return $action;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Postcard $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Postcard $model)
    {
        return Postcard::select('id','image','name')
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
                    ->setTableId('postcard-table')
                    ->columns($this->getColumns())
                    ->setTableAttributes(['class'=>'table datatable pt-4 pb-4'])
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
            Column::make('name'),
            Column::make('image'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
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
        return 'Postcard_' . date('YmdHis');
    }
}
