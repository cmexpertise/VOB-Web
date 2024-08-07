<?php

namespace App\DataTables;

use App\Models\Quete;
use App\Models\Quote;
use App\Models\Quote_category;
use App\Models\QuotesCategory;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QuotesCategoryDataTable extends DataTable
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
                <a href="'.route("quotes.add_quote",["id" => encrypt($row->id),"name"=>encrypt($row->name)]).'" class="btn btn-primary add_video">
                    <i class="bx bxs-plus-square"></i></a>
                <button type="button" class="btn btn-danger deleteQuoteCategory" data-id="'.encrypt($row->id).'" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="ri-delete-bin-5-fill"></i>
                </button>';
                return $action;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\QuotesCategory $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Quote $model)
    {
        return Quote_category::select('id','name','korean_name','spanish_name','portuguese_name','filipino_name')
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
                    ->setTableId('quotescategory-table')
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
            Column::make('name')->title('English Title'),
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
        return 'QuotesCategory_' . date('YmdHis');
    }
}
