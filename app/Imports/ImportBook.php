<?php

namespace App\Imports;

use App\Models\Book;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportBook implements ToModel, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 2; // Skip the first row (header row)
    }

    public function model(array $row){
        try {
            $getType = $row[0];
            $english_name = $row[1];
            $korean_name = $row[2];
            $spanish_name = $row[3];
            $portuguese_name = $row[4];
            $filipino_name = $row[5];
            $english_description = $row[6];
            $korean_description = $row[7];
            $spanish_description = $row[8];
            $portuguese_description = $row[9];
            $filipino_description = $row[10];

            if($getType == 'old_testament'){
                $type = 2;
            }elseif ($getType == 'new_testament') {
                $type = 1;
            }else{
                $type = 1;
            }

            $book = new Book();
            $book->type = $type;
            $book->name = $english_name;
            $book->korean_name = $korean_name;
            $book->spanish_name = $spanish_name;
            $book->portuguese_name = $portuguese_name;
            $book->filipino_name = $filipino_name;
            $book->description = $english_description;
            $book->korean_description = $korean_description;
            $book->spanish_description = $spanish_description;
            $book->portuguese_description = $portuguese_description;
            $book->filipino_description = $filipino_description;
            $book->save();
            
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}
