<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspiration extends Model
{
    use HasFactory;
    function inspiration_videos(){
        return $this->hasMany(Inspiration_video::class);
    }
}
