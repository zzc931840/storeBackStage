<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userLike extends Model
{
    //
      protected $table = 'userlike';

      function GetUser(){
              return $this->hasMany('App\userInfo','fid','uid');
      }
}
