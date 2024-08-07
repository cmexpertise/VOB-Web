<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class ImportChapter implements ToModel, WithStartRow
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
            $bookName = $row[0];
            $english_name = $row[1];
            $korean_name = $row[2];
            $spanish_name = $row[3];
            $portuguese_name = $row[4];
            $filipino_name = $row[5];
            $english_video_link = $row[6];
            $korean_video_link = $row[7];
            $spanish_video_link = $row[8];
            $portuguese_video_link = $row[9];
            $filipino_video_link = $row[10];
            $english_audio_link = $row[11];
            $korean_audio_link = $row[12];
            $spanish_audio_link = $row[13];
            $portuguese_audio_link = $row[14];
            $filipino_audio_link = $row[15];
            $english_srt_link = $row[16];
            $korean_srt_link = $row[17];
            $spanish_srt_link = $row[18];
            $portuguese_srt_link = $row[19];
            $filipino_srt_link = $row[20];
            $english_description = $row[21];
            $korean_description = $row[22];
            $spanish_description = $row[23];
            $portuguese_description = $row[24];
            $filipino_description = $row[25];
            $video_image = $row[26];
            $audio_image = $row[27];

            $book = Book::where('name',$bookName)
                ->select('id')
                ->get();
            // dd($book[0]->id);
            if(isset($book)){
                $book_id = $book[0]->id;
            }else{
                exit;
            }

            $chapter = new Chapter();
            $chapter->book_id = $book_id;
            $chapter->name = $english_name;
            $chapter->korean_name = $korean_name;
            $chapter->spanish_name = $spanish_name;
            $chapter->portuguese_name = $portuguese_name;
            $chapter->filipino_name = $filipino_name;
            $chapter->description = $english_description;
            $chapter->korean_description = $korean_description;
            $chapter->spanish_description = $spanish_description;
            $chapter->portuguese_description = $portuguese_description;
            $chapter->filipino_description = $filipino_description;
            $chapter->video = $english_video_link;
            $chapter->korean_video = $korean_video_link;
            $chapter->spanish_video = $spanish_video_link;
            $chapter->portuguese_video = $portuguese_video_link;
            $chapter->filipino_video = $filipino_video_link;
            $chapter->audio = $english_audio_link;
            $chapter->korean_audio = $korean_audio_link;
            $chapter->spanish_audio = $spanish_audio_link;
            $chapter->portuguese_audio = $portuguese_audio_link;
            $chapter->filipino_audio = $filipino_audio_link;
            $chapter->srt = $english_srt_link;
            $chapter->korean_srt = $korean_srt_link;
            $chapter->spanish_srt = $spanish_srt_link;
            $chapter->portuguese_srt = $portuguese_srt_link;
            $chapter->filipino_srt = $filipino_srt_link;
            $chapter->video_image = "chapter/video/".$video_image.".png";
            $chapter->audio_image = "chapter/audio/".$audio_image.".png";
            $chapter->save();
            
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}
