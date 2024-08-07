<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    function book(){
        return $this->belongsTo(Book::class);
    }

    function bookmark(){
        return $this->hasOne(Bookmark::class,'video_id');
    }
    function bookmarks(){
        return $this->hasMany(Bookmark::class,'video_id');
    }
    function duration(){
        return $this->hasMany(Duration::class,'video_id');
    }

}
