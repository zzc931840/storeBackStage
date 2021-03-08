<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogContent extends Model
{
    //
    protected $table = 'blog_content';
    public function GetDirectory(){
         return $this->belongsTo('App\BlogDirectory','did','id');
    }
    public function GetUser(){
        return $this->belongsTo('App\Users','uid','id')->with('UserInfo');
    }
    public function GetBrowse(){
        return $this->hasMany('App\Browse','did','id');
    }
    public function GetComment(){
        return $this->hasMany('App\Comment','did','id');
    }
    public function GetUserLike(){
        return $this->hasMany('App\userLike','pid','id')->with('GetUser');
    }
    public function GetUserLikeCount(){
        return $this->hasMany('App\userLike','pid','id');
    }
}
