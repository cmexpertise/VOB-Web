<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportMeditation implements FromCollection, WithHeadings, WithEvents, WithStrictNullComparison
{
    protected $header;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $header){   
        $this->header = $header;
    }

    public function collection()
    {
        return collect();
    }

    public function headings(): array{
        return $this->header;
    }

    public function registerEvents(): array{
        return [
            
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getStyle('A1:Z1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
                $row_count = 150;
                $column_count = 50;
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
