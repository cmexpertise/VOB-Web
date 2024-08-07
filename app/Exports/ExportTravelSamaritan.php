<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExportTravelSamaritan implements FromCollection, WithHeadings, WithEvents, WithStrictNullComparison
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

                $event->sheet->getStyle('A1:AB1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $row_count = 150;
                $column_count = 50;
                $drop_columnP = 'P';
                $n = ['yes','no'];
                $validation = $event->sheet->getCell("{$drop_columnP}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST );
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1(sprintf('"%s"',implode(',',$n)));
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_columnP}{$i}")->setDataValidation(clone $validation);
                }

                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
