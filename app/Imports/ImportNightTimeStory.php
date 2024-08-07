<?php

namespace App\Imports;

use App\Models\Night_time_story;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportNightTimeStory implements ToModel, WithStartRow
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
            
            $english_name = $row[0];
            $korean_name = $row[1];
            $spanish_name = $row[2];
            $portuguese_name = $row[3];
            $filipino_name = $row[4];
            $description = $row[5];
            $korean_description = $row[6];
            $spanish_description = $row[7];
            $portuguese_description = $row[8];
            $filipino_description = $row[9];  
            $image = $row[10];   
            $book = new Night_time_story();
            $book->name = $english_name;
            $book->korean_name = $korean_name;
            $book->spanish_name = $spanish_name;
            $book->portuguese_name = $portuguese_name;
            $book->filipino_name = $filipino_name;
            $book->description = $description;
            $book->korean_description = $korean_description;
            $book->spanish_description = $spanish_description;
            $book->portuguese_description = $portuguese_description;
            $book->filipino_description = $filipino_description;
            $book->image = "night_time_story/".$image;
            $book->save();
            
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}
