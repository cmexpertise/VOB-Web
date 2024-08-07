<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Night_time_story extends Model
{
    use HasFactory;
    function night_time_story_videos(){
        return $this->hasMany(Night_time_story_video::class);
    }
}
