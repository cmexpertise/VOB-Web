<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ExportBook implements FromCollection, WithHeadings ,WithEvents
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
                $event->sheet->getStyle('A1:T1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
                $drop_columnA = 'A';
                $n = ['old_testament','new_testament'];
            
                $validationB = $event->sheet->getCell("{$drop_columnA}2")->getDataValidation();
                $validationB->setType(DataValidation::TYPE_LIST );
                $validationB->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validationB->setAllowBlank(false);
                $validationB->setShowInputMessage(true);
                $validationB->setShowErrorMessage(true);
                $validationB->setShowDropDown(true);
                $validationB->setErrorTitle('Input error');
                $validationB->setError('Value is not in list.');
                $validationB->setPromptTitle('Pick from list');
                $validationB->setPrompt('Please pick a value from the drop-down list.');
                $validationB->setFormula1(sprintf('"%s"',implode(',',$n)));

                $row_count = 100;
                $column_count = 20;
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_columnA}{$i}")->setDataValidation(clone $validationB);
                }

                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
