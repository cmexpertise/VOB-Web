<?php

namespace App\DataTables;

use App\Models\User_postcard;
use App\Models\UserPostcard;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserPostcardDataTable extends DataTable
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
                        $q->orWhereHas('user', function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%{$keyword}%");
                        });
                    });
                }
            })
            ->addColumn('sr_no', function () use (&$counter) {
                return $counter++;
            })
            ->addColumn('image', function ($row) {
                return '<img data-src="'.asset("storage/".$row->image).'" class="table-audio-img lazyload">';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name;
            }) 
            ->rawColumns(['image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserPostcard $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return User_postcard::with(['user' => function ($query) {
            $query->select('id','name');
        }])
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
                    ->setTableId('userpostcard-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->setTableAttributes(['class'=>'table datatable pt-4 pb-4'])
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
            Column::make('user_name'),
            Column::make('image'),
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-left'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'UserPostcard_' . date('YmdHis');
    }
}
