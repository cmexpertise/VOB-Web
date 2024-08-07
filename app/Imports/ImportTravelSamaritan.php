<?php

namespace App\Imports;

use App\Models\Travel_samaritan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportTravelSamaritan implements ToModel, WithStartRow
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
            $video = $row[5];
            $korean_video = $row[6];
            $spanish_video = $row[7];
            $portuguese_video = $row[8];
            $filipino_video = $row[9];
            $srt = $row[10];
            $korean_srt = $row[11];
            $spanish_srt = $row[12];
            $portuguese_srt = $row[13];
            $filipino_srt = $row[14];   
            $featured_video = $row[15];   
            $image = $row[16];   

            $book = new Travel_samaritan();
            $book->name = $english_name;
            $book->korean_name = $korean_name;
            $book->spanish_name = $spanish_name;
            $book->portuguese_name = $portuguese_name;
            $book->filipino_name = $filipino_name;
            $book->video = $video;
            $book->korean_video = $korean_video;
            $book->spanish_video = $spanish_video;
            $book->portuguese_video = $portuguese_video;
            $book->filipino_video = $filipino_video;
            $book->srt = $srt;
            $book->korean_srt = $korean_srt;
            $book->spanish_srt = $spanish_srt;
            $book->portuguese_srt = $portuguese_srt;
            $book->filipino_srt = $filipino_srt;
            $book->featured_video = ($featured_video=='no')?0:1;
            $book->image = "travel_samaritan/".$image;
            $book->save();
            
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}
