<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily_prayer extends Model
{
    use HasFactory;

    function bookmarks(){
        return $this->hasMany(Bookmark::class,'video_id');
    }

    function duration(){
        return $this->hasMany(Duration::class,'video_id');
    }
}
