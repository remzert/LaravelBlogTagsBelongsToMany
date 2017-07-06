<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use Concern\Taggable;
    public $fillable = ['name', 'content'];
    
   
}
