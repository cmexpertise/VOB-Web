<?php

namespace App\Imports;

use App\Models\Daily_prayer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportBroadcast implements ToModel, WithStartRow
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
            // dd($row);
            $english_name = $row[0];
            $korean_name = $row[1];
            $spanish_name = $row[2];
            $portuguese_name = $row[3];
            $filipino_name = $row[4];
            $title = $row[5];
            $korean_title = $row[6];
            $spanish_title = $row[7];
            $portuguese_title = $row[8];
            $filipino_title = $row[9];
            $video = $row[10];
            $korean_video = $row[11];
            $spanish_video = $row[12];
            $portuguese_video = $row[13];
            $filipino_video = $row[14];   
            $image = $row[15];   

            $book = new Daily_prayer();
            $book->name = $english_name;
            $book->korean_name = $korean_name;
            $book->spanish_name = $spanish_name;
            $book->portuguese_name = $portuguese_name;
            $book->filipino_name = $filipino_name;
            $book->title = $title;
            $book->korean_title = $korean_title;
            $book->spanish_title = $spanish_title;
            $book->portuguese_title = $portuguese_title;
            $book->filipino_title = $filipino_title;
            $book->video = $video;
            $book->korean_video = $korean_video;
            $book->spanish_video = $spanish_video;
            $book->portuguese_video = $portuguese_video;
            $book->filipino_video = $filipino_video;
            $book->image = "daily_prayer/".$image;
            $book->save();
            
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}
