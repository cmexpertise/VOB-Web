<?php

namespace App\DataTables;

use App\Models\Inspiration_video;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class Inspiration_videoDataTable extends DataTable
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
            ->addColumn('video', function ($row) {
                return $row->video;
            })
            ->addColumn('image', function ($row) {
                return '<img data-src="'.asset("storage/".$row->image).'" class="table-audio-img lazyload">';
            })
            ->addColumn('action', function($row){
                
                $action = '<a href="#" class="btn btn-primary edit" data-id="'.$row->id.'" >
                <i class="ri-edit-box-fill"></i></a>
                <button type="button" class="btn btn-danger deleteInspirationVideo" data-id="'.encrypt($row->id).'" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="ri-delete-bin-5-fill"></i>
                </button>';
                return $action;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Inspiration_video $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Request $request,Inspiration_video $model)
    {
        $id = decrypt($request->id);
        return Inspiration_video::select('id','name','video','image')
            ->where('inspiration_id',$id)
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
                    ->setTableId('inspiration_video-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->setTableAttributes(['class'=>'table datatable pt-4 pb-4'])
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
            Column::make('video')->title('Video Link'),
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
        return 'Inspiration_video_' . date('YmdHis');
    }
}
