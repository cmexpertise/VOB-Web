<?php

namespace App\DataTables;

use App\Models\Chapter;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ChaptersDataTable extends DataTable
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
                        $q->orWhereHas('book', function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%{$keyword}%");
                        })
                        ->orWhere('name', 'LIKE', "%{$keyword}%");
                    });
                }
            })
            ->addColumn('sr_no', function () use (&$counter) {
                return $counter++;
            })
            ->addColumn('book_name', function ($row) {
                return $row->book->name;
            })
            ->addColumn('video_image', function ($row) {
                return '<img data-src="'.asset("storage/".$row->video_image).'" class="table-audio-img lazyload">';
            })
            // ->addColumn('audio_image', function ($row) {
            //     return '<img data-src="'.asset("storage/".$row->audio_image).'" class="table-audio-img lazyload">';
            // })
            ->addColumn('action', function($row){
                
                $action = '<a href="#" class="btn btn-primary edit" data-id="'.$row->id.'" >
                <i class="ri-edit-box-fill"></i>
                        </a>
                        <button type="button" class="btn btn-danger deleteChapter" data-id="'.encrypt($row->id).'" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="ri-delete-bin-5-fill"></i>
                        </button>';
            return $action;
        })
        ->rawColumns(['audio_image','action','video_image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Chapter $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Chapter $model)
    {
        return Chapter::with(['book' => function ($query){
            $query->select('id','name');
        }])
        ->select('id','book_id','name','korean_name','spanish_name','portuguese_name','filipino_name','video_image','audio_image')
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
                    ->setTableId('chapters-table')
                    ->columns($this->getColumns())
                    ->setTableAttributes(['class'=>'table datatable pt-4 pb-4'])
                    ->dom('Bfrtip')
                    ->minifiedAjax()
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
            Column::make('book_name')->title('Book Name'),
            Column::make('name')->title('Chapter Name'),
            Column::make('video_image')->title('Video Image'),
            // Column::make('audio_image')->title('Audio Image'),
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
        return 'Chapters_' . date('YmdHis');
    }
}
