<?php

namespace App\Imports;

use App\Models\Night_time_story;
use App\Models\Night_time_story_video;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportNightTimeStoryVideos implements ToModel, WithStartRow
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

            $nightTimeStory = $row[0];
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
            $description = $row[11];
            $korean_description = $row[12];
            $spanish_description = $row[13];
            $portuguese_description = $row[14];
            $filipino_description = $row[15];
            $image = $row[16];
            $book = Night_time_story::where('name',$nightTimeStory)
                ->select('id')
                ->get();
            if(isset($book)){
                $book_id = $book[0]->id;
            }else{
                exit;
            }

            $chapter = new Night_time_story_video();
            $chapter->night_time_story_id = $book_id;
            $chapter->name = $english_name;
            $chapter->korean_name = $korean_name;
            $chapter->spanish_name = $spanish_name;
            $chapter->portuguese_name = $portuguese_name;
            $chapter->filipino_name = $filipino_name;
            $chapter->description = $description;
            $chapter->korean_description = $korean_description;
            $chapter->spanish_description = $spanish_description;
            $chapter->portuguese_description = $portuguese_description;
            $chapter->filipino_description = $filipino_description;
            $chapter->video = $english_video_link;
            $chapter->korean_video = $korean_video_link;
            $chapter->spanish_video = $spanish_video_link;
            $chapter->portuguese_video = $portuguese_video_link;
            $chapter->filipino_video = $filipino_video_link;
            $chapter->image = "night_time_story_video/".$image;
            $chapter->save();
            
            
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage());
        }
    }
}