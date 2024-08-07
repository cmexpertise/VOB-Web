<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    use HasFactory;

    function chapter(){
        return $this->belongsTo(Chapter::class,'video_id');
    }

    function user(){
        return $this->belongsTo(User::class);
    }
    
    function daily_prayer(){
        return $this->belongsTo(Daily_prayer::class,'video_id');
    }

    function meditaion(){
        return $this->belongsTo(Meditation::class,'video_id');
    }
    
    function night_time_story_video(){
        return $this->belongsTo(Night_time_story_video::class,'video_id');
    }

    function inspiration_video(){
        return $this->belongsTo(Inspiration_video::class,'video_id');
    }
    
    function travel_samaritan(){
        return $this->belongsTo(Travel_samaritan::class,'video_id');
    }
}
