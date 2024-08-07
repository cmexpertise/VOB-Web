<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportUpdatedChapterAudios implements ToModel, WithStartRow
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
            
            $english_audio_link = $row[11];
            $korean_audio_link = $row[12];
            $spanish_audio_link = $row[13];
            $portuguese_audio_link = $row[14];
            $filipino_audio_link = $row[15];
            $book = Book::where('name',$bookName)
                ->select('id')
                ->get();
            if(isset($book)){
                $book_id = $book[0]->id;
            }else{
                exit;
            }
            $chapters = Chapter::where('book_id',$book_id)
                ->where('name',$english_name)
                ->update([
                    'audio' => $english_audio_link,
                    'korean_audio' => $korean_audio_link,
                    'spanish_audio' => $spanish_audio_link,
                    'portuguese_audio' => $portuguese_audio_link,
                    'filipino_audio' => $filipino_audio_link
                ]);
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}
