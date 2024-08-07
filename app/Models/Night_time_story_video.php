<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Night_time_story_video extends Model
{
    use HasFactory;
    function night_time_story(){
        return $this->belongsTo(Night_time_story::class);
    }

    function bookmarks(){
        return $this->hasMany(Bookmark::class,'video_id');
    }

    function duration(){
        return $this->hasMany(Duration::class,'video_id');
    }
}
