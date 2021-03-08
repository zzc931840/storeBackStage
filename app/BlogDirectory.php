<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogDirectory extends Model
{
    //
    protected $table = 'blog_directory';

    public function GetContent(){
         return $this->hasMany('App\BlogContent','did','id')->select('did','title','id');
    }
}
